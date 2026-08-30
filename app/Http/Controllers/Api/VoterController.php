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

        $voters = Voter::where('workspace_id', $request->workspace_id)->latest()->paginate(20);

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
