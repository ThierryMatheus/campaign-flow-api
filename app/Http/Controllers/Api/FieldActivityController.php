<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FieldActivity\StoreFieldActivityRequest;
use App\Http\Requests\FieldActivity\UpdateFieldActivityRequest;
use App\Http\Resources\FieldActivityResource;
use App\Models\FieldActivity;
use Illuminate\Http\Request;

class FieldActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', FieldActivity::class);

        $query = FieldActivity::where('workspace_id', $request->workspace_id)->with(['voter', 'team', 'user']);

        if($request->filled('type')){
            $query->where('type', $request->type);
        }

        if($request->filled('result')){
            $query->where('result', $request->result);
        }

        if($request->filled('team_id')){
            $query->where('team_id', $request->team_id);
        }

        if($request->filled('voter_id')){
            $query->where('voter_id', $request->voter_id);
        }

        if($request->filled('user_id')){
            $query->where('user_id', $request->user_id);
        }

        if($request->filled('from')){
            $query->where('performed_at', '>=' ,$request->from);
        }

        if($request->filled('to')){
            $query->where('performed_at', '<=' ,$request->to);
        }

        $activities = $query->latest('performed_at')->paginate(20);

        return FieldActivityResource::collection($activities);
    }

    public function store(StoreFieldActivityRequest $request)
    {
        $this->authorize('create', FieldActivity::class);

        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $activity = FieldActivity::create($data);

        return (new FieldActivityResource($activity))->response()->setStatusCode(201);
    }

    public function show(FieldActivity $fieldActivity)
    {
        $this->authorize('view', $fieldActivity);

        $fieldActivity->load(['voter', 'team', 'user']);

        return new FieldActivityResource($fieldActivity);
    }

    public function update(UpdateFieldActivityRequest $request, FieldActivity $fieldActivity)
    {
        $this->authorize('update', $fieldActivity);

        $fieldActivity->update($request->validated());

        return new FieldActivityResource($fieldActivity);
    }

    public function destroy(FieldActivity $fieldActivity)
    {
        $this->authorize('delete', $fieldActivity);

        $fieldActivity->delete();

        return response()->json(['message' => 'Field activity deleted successfully']);
}
}
