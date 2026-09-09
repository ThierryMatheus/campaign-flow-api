<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'workspace_id' => ['nullable', 'exists:workspaces,id'],
        ]);

        $query = Activity::query()
            ->with(['causer', 'subject'])
            ->latest();

        // Filtra por workspace quando o subject tiver workspace_id
        if ($request->filled('workspace_id')) {
            $workspaceId = $request->workspace_id;

            $query->where(function ($q) use ($workspaceId) {
                $q->whereHasMorph('subject', [
                    \App\Models\Voter::class,
                    \App\Models\Team::class,
                    \App\Models\FieldActivity::class,
                    \App\Models\Demand::class,
                    \App\Models\AgendaItem::class,
                    \App\Models\Transaction::class,
                    \App\Models\Workspace::class,
                ], function ($q) use ($workspaceId) {
                    $q->where('workspace_id', $workspaceId);
                });
            });
        }

        $logs = $query->paginate(30);

        return response()->json($logs);
    }
}
