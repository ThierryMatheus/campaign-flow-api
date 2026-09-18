<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AgendaItem\StoreAgendaItemRequest;
use App\Http\Requests\AgendaItem\UpdateAgendaItemRequest;
use App\Http\Resources\AgendaItemResource;
use App\Models\AgendaItem;
use Illuminate\Http\Request;

class AgendaItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', AgendaItem::class);

        $query = AgendaItem::query()->where('workspace_id', $request->workspace_id)->with(['team', 'voter', 'creator']);

        if($request->filled('status')){
            $query->where('status', $request->status);
        }

        if($request->filled('type')){
            $query->where('type', $request->type);
        }

        if($request->filled('from')){
            $query->where('starts_at','>=', $request->from);
        }

        if($request->filled('to')){
            $query->where('ends_at','<=' , $request->to);
        }

        if($request->filled('status')){
            $query->where('status', $request->status);
        }

        if($request->filled('search')){
            $search = $request->search;
            $query->where(function($q) use ($search){
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('starts_at')->paginate(20);

        return AgendaItemResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAgendaItemRequest $request)
    {
        $this->authorize('create', AgendaItem::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $item = AgendaItem::create($data);

        return (new AgendaItemResource($item))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AgendaItem $agendaItem)
    {
        $this->authorize('view', $agendaItem);
        $agendaItem->load(['team', 'voter', 'creator']);

        return new AgendaItemResource($agendaItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgendaItemRequest $request, AgendaItem $agendaItem)
    {
        $this->authorize('update', $agendaItem);
        $agendaItem->update($request->validated());

        return new AgendaItemResource($agendaItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AgendaItem $agendaItem)
    {
        $this->authorize('delete', $agendaItem);
        $agendaItem->delete();

        return response()->json(['message' => 'Agenda item deleted successfully']);
    }
}
