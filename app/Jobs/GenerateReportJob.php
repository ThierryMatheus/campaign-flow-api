<?php

namespace App\Jobs;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\Transaction;
use App\Models\Voter;
use App\Services\DashboardService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\File;

class GenerateReportJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Report $report) {}

    public function handle(DashboardService $dashboard): void
    {
        $this->report->update(['status' => ReportStatus::Processing]);

        try {
            $path = "reports/{$this->report->workspace_id}/report-{$this->report->id}.csv";
            $usePython = filter_var(env('REPORTS_USE_PYTHON', false), FILTER_VALIDATE_BOOL);

            if ($usePython) {
                $data = match ($this->report->type->value) {
                    'dashboard' => $dashboard->summary($this->report->workspace_id),
                    'voters' => $this->votersData(),
                    'transactions' => $this->transactionsData(),
                };

                $absoluteCsv = Storage::disk('local')->path($path);
                $absoluteJson = Storage::disk('local')->path("reports/tmp-{$this->report->id}.json");

                File::ensureDirectoryExists(dirname($absoluteCsv));
                File::put($absoluteJson, json_encode($data, JSON_UNESCAPED_UNICODE));

                $python = base_path(
                    PHP_OS_FAMILY === 'Windows'
                        ? 'reports-python/venv/Scripts/python.exe'
                        : 'reports-python/venv/bin/python'
                );
                $script = base_path('reports-python/generate_report.py');

                $result = Process::timeout(120)->run([
                    $python,
                    $script,
                    '--type', $this->report->type->value,
                    '--input', $absoluteJson,
                    '--output', $absoluteCsv,
                ]);

                File::delete($absoluteJson);

                if ($result->failed()) {
                    throw new \RuntimeException('Python report failed: '.$result->errorOutput());
                }
            } else {
                $csv = match ($this->report->type->value) {
                    'dashboard' => $this->dashboardCsv($dashboard),
                    'voters' => $this->votersCsv(),
                    'transactions' => $this->transactionsCsv(),
                };

                Storage::disk('local')->put($path, $csv);
            }

            $absoluteCsv = Storage::disk('local')->path($path);
            $absolutePng = preg_replace('/\.csv$/i', '.png', $absoluteCsv);
            $zipRelative = preg_replace('/\.csv$/i', '.zip', $path);
            $absoluteZip = Storage::disk('local')->path($zipRelative);

            $zip = new \ZipArchive();
            if ($zip->open($absoluteZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException('Could not create ZIP');
            }

            $zip->addFile($absoluteCsv, basename($absoluteCsv));

            if (is_file($absolutePng)) {
                $zip->addFile($absolutePng, basename($absolutePng));
            }

            $zip->close();

            $path = $zipRelative;

            $this->report->update([
                'status' => ReportStatus::Ready,
                'file_path' => $path,
                'finished_at' => now(),
                'error_message' => null,
            ]);
        } catch (\Throwable $e) {
            $this->report->update([
                'status' => ReportStatus::Failed,
                'error_message' => $e->getMessage(),
                'finished_at' => now(),
            ]);

            throw $e;
        }
    }

    private function dashboardCsv(DashboardService $dashboard): string
    {
        $s = $dashboard->summary($this->report->workspace_id);

        $lines = [
            ['metric', 'value'],
            ['voters_total', $s['voters']['total']],
            ['supporters', $s['voters']['by_status']['supporter'] ?? 0],
            ['undecided', $s['voters']['by_status']['undecided'] ?? 0],
            ['demands_open', $s['demands']['open']],
            ['donations', $s['finance']['total_donations']],
            ['expenses', $s['finance']['total_expenses']],
            ['balance', $s['finance']['balance']],
        ];

        return $this->toCsv($lines);
    }

    private function votersCsv(): string
    {
        $lines = [['id', 'name', 'cpf', 'status', 'neighborhood', 'city']];

        Voter::where('workspace_id', $this->report->workspace_id)
            ->orderBy('id')
            ->chunk(500, function ($voters) use (&$lines) {
                foreach ($voters as $v) {
                    $lines[] = [
                        $v->id,
                        $v->name,
                        $v->cpf,
                        $v->status?->value ?? $v->status,
                        $v->neighborhood,
                        $v->city,
                    ];
                }
            });

        return $this->toCsv($lines);
    }

    private function transactionsCsv(): string
    {
        $lines = [['id', 'type', 'title', 'amount', 'occurred_at', 'category']];

        Transaction::where('workspace_id', $this->report->workspace_id)
            ->orderBy('id')
            ->chunk(500, function ($rows) use (&$lines) {
                foreach ($rows as $t) {
                    $lines[] = [
                        $t->id,
                        $t->type?->value ?? $t->type,
                        $t->title,
                        $t->amount,
                        $t->occurred_at?->format('Y-m-d'),
                        $t->category?->value ?? $t->category,
                    ];
                }
            });

        return $this->toCsv($lines);
    }

    private function toCsv(array $lines): string
    {
        $fh = fopen('php://temp', 'r+');
        foreach ($lines as $line) {
            fputcsv($fh, $line);
        }
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        return $csv ?: '';
    }
    private function votersData(): array
    {
        $headers = ['id', 'name', 'cpf', 'status', 'neighborhood', 'city'];
        $rows = [];

        Voter::where('workspace_id', $this->report->workspace_id)
            ->orderBy('id')
            ->chunk(500, function ($voters) use (&$rows) {
                foreach ($voters as $v) {
                    $rows[] = [
                        $v->id,
                        $v->name,
                        $v->cpf,
                        $v->status?->value ?? $v->status,
                        $v->neighborhood,
                        $v->city,
                    ];
                }
            });

        return ['headers' => $headers, 'rows' => $rows];
    }

    private function transactionsData(): array
    {
        $headers = ['id', 'type', 'title', 'amount', 'occurred_at', 'category'];
        $rows = [];

        Transaction::where('workspace_id', $this->report->workspace_id)
            ->orderBy('id')
            ->chunk(500, function ($items) use (&$rows) {
                foreach ($items as $t) {
                    $rows[] = [
                        $t->id,
                        $t->type?->value ?? $t->type,
                        $t->title,
                        $t->amount,
                        $t->occurred_at?->format('Y-m-d'),
                        $t->category?->value ?? $t->category,
                    ];
                }
            });

        return ['headers' => $headers, 'rows' => $rows];
    }
}
