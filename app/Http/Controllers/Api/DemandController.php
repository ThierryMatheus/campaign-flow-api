<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Demand\StoreDemandRequest;
use App\Http\Requests\Demand\UpdateDemandRequest;
use App\Http\Resources\DemandResource;
use App\Models\Demand;
use Illuminate\Http\Request;

class DemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Demand::class);

        $query = Demand::where('workspace_id', $request->workspace_id)->with(['voter', 'creator', 'assignee']);

        if($request->filled('status')){
            $query->where('status', $request->status);
        }

        if($request->filled('priority')){
            $query->where('priority', $request->priority);
        }

        if($request->filled('team_id')){
            $query->where('team_id', $request->team_id);
        }

        if($request->filled('assigned_to')){
            $query->where('assigned_to', $request->assigned_to);
        }

        if($request->filled('search')){
            $search = $request->search;
            $query->where(function($q) use ($search){
                $q->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }


        $demands = $query->latest()->paginate(20);

        return DemandResource::collection($demands);    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDemandRequest $request)
    {
        $this->authorize('create', Demand::class);

        $data = $request->validated();

        $data['created_by'] = $request->user()->id;

        $demand = Demand::create($data);

        return new DemandResource($demand);
    }

    /**
     * Display the specified resource.
     */
    public function show(Demand $demand)
    {
        $this->authorize('view', $demand);
        $demand->load('voter', 'creator', 'assignee');

        return new DemandResource($demand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDemandRequest $request, Demand $demand)
    {
        $this-> authorize('update', $demand);

        $data = $request->validated();

        if(isset($data['status']) && $data['status'] == 'resolved' && !$demand->resolved_at){
            $data->resolved_at = now();
        }

        $demand->update($data);

        return new DemandResource($demand);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demand $demand)
    {
        $this->authorize('delete', $demand);
        $demand->delete();

        return response()->json(['message' => 'Demand deleted successfully']);
    }
}
