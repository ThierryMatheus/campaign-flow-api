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

class GenerateReportJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Report $report) {}

    public function handle(DashboardService $dashboard): void
    {
        $this->report->update(['status' => ReportStatus::Processing]);

        try {
            $csv = match ($this->report->type->value) {
                'dashboard' => $this->dashboardCsv($dashboard),
                'voters' => $this->votersCsv(),
                'transactions' => $this->transactionsCsv(),
            };

            $path = "reports/{$this->report->workspace_id}/report-{$this->report->id}.csv";
            Storage::disk('local')->put($path, $csv);

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
}
