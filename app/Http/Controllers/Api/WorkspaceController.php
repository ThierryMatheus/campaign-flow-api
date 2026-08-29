<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workspace\StoreWorkspaceRequest;
use App\Http\Requests\Workspace\UpdateWorkspaceRequest;
use App\Http\Resources\WorkspaceResource;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Workspace::class);

        $workspaces = $request->user()->workspaces()->latest()->get();

        return WorkspaceResource::collection($workspaces);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkspaceRequest $request)
    {
        $this->authorize('create', Workspace::class);

        $workspace = Workspace::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(6),
            'type' => $request->type,
            'election_year' => $request->election_year,
            'city' => $request->city,
            'state' => $request->state,
            'status' => $request->input('status', 'draft'),
            'owner_id' => $request->user()->id
        ]);

        $workspace->users()->attach($request->user()->id, [
            'role' => 'admin',
            'is_primary' => true
        ], 201);

        return new WorkspaceResource($workspace);
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspace $workspace)
    {
        $this->authorize('view', $workspace);

        return new WorkspaceResource($workspace);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkspaceRequest $request, Workspace $workspace)
    {
        $this->authorize('update', $workspace);

        $workspace->update($request->validated());

        return new WorkspaceResource($workspace);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace)
    {
        $this->authorize('delete', $workspace);

        $workspace->delete();

        return response()->json(['message' => 'Workspace deleted successfully']);
    }
}
