<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreReportRequest;
use App\Jobs\GenerateReportJob;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Report::class);

        $request->validate([
            'workspace_id' => ['required', 'exists:workspaces,id'],
        ]);

        $belongs = $request->user()
            ->workspaces()
            ->where('workspaces.id', $request->workspace_id)
            ->exists();

        abort_unless($belongs, 403);

        $reports = Report::where('workspace_id', $request->workspace_id)
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json($reports);
    }

    public function store(StoreReportRequest $request)
    {
        $this->authorize('create', Report::class);

        $belongs = $request->user()
            ->workspaces()
            ->where('workspaces.id', $request->workspace_id)
            ->exists();

        abort_unless($belongs, 403);

        $report = Report::create([
            'workspace_id' => $request->workspace_id,
            'user_id' => $request->user()->id,
            'type' => $request->type,
            'format' => $request->input('format', 'csv'),
            'status' => 'pending',
        ]);

        GenerateReportJob::dispatch($report);

        return response()->json($report, 201);
    }

    public function show(Report $report)
    {
        $this->authorize('view', $report);

        return response()->json($report);
    }

    public function download(Report $report)
    {
        $this->authorize('download', $report);

        abort_unless($report->status?->value === 'ready' && $report->file_path, 409, 'Report not ready');

        $absolute = Storage::disk('local')->path($report->file_path);

        return response()->download($absolute, "report-{$report->id}.csv");
    }
}
