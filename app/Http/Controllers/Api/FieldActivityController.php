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

        $activities = FieldActivity::where('workspace_id', $request->workspace_id)
            ->with(['voter', 'team', 'user'])
            ->latest('performed_at')
            ->paginate(20);

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
