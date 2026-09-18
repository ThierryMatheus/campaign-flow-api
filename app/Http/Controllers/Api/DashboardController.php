<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request, DashboardService $service)
    {
        $request->validate([
            'workspace_id' => ['required', 'exists:workspaces,id'],
        ]);

        // Garante que o usuário pertence ao workspace
        $belongs = $request->user()
            ->workspaces()
            ->where('workspaces.id', $request->workspace_id)
            ->exists();

        abort_unless($belongs, 403);

        return response()->json(
            $service->summary((int) $request->workspace_id)
        );
    }
}
