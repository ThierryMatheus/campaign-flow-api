<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voter\StoreVoterRequest;
use App\Http\Requests\Voter\UpdateVoterRequest;
use App\Http\Resources\VoterResource;
use App\Models\Voter;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Voter::class);

        $query = Voter::where('workspace_id', $request->workspace_id);

        if($request->filled('status')){
            $query->where('status', $request->status);
        }

        if($request->filled('origin')){
            $query->where('origin', $request->origin);
        }

        if($request->filled('neighborhood')){
            $query->where('neighborhood', 'like','%' . $request->neighborhood . '%');
        }

        if($request->filled('city')){
            $query->where('city', 'like','%' . $request->city . '%');
        }

        if($request->filled('search')){
            $search = $request->search;
            $query->where(function ($q) use ($search){
                $q->where('name','like', "%{$search}%");
                $q->where('cpf','like', "%{$search}%");
            });
        }

        $voters = $query->latest()->paginate(20);

        return VoterResource::collection($voters);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoterRequest $request)
    {
        $this->authorize('create', Voter::class);

        $voter = Voter::create($request->validated());

        return (new VoterResource($voter))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Voter $voter)
    {
        $this->authorize('view', $voter);

        return new VoterResource($voter);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoterRequest $request, Voter $voter)
    {
        $this->authorize('update', $voter);

        $voter->update($request->validated());

        return new VoterResource($voter);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voter $voter)
    {
        $this->authorize('delete', $voter);

        $voter->delete();

        return response()->json(['message' => 'Voter deleted successfully']);
    }
}
