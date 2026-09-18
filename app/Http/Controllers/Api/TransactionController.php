<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Transaction::class);

        $query = Transaction::query()->where('workspace_id', $request->workspace_id)->with('creator');

        if($request->filled('type')){
            $query->where('type', $request->type);
        }

        if($request->filled('category')){
            $query->where('category', $request->category);
        }

        if($request->filled('from')){
            $query->where('occurred_at', '>=', $request->from);
        }

        if($request->filled('to')){
            $query->where('occurred_at', '<=', $request->to);
        }

        if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('donor_or_vendor', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest('occurred_at')->paginate(20);

        return TransactionResource::collection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $this->authorize('create', Transaction::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $transaction = Transaction::create($data);

        return (new TransactionResource($transaction))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        $transaction->load('creator');

        return new TransactionResource($transaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $transaction->update($request->validated());

        return new TransactionResource($transaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}
