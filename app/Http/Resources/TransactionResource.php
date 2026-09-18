<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workspace_id' => $this->workspace_id,
            'created_by' => $this->created_by,
            'type' => $this->type,
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'occurred_at' => $this->occurred_at?->format('Y-m-d'),
            'payment_method' => $this->payment_method,
            'donor_or_vendor' => $this->donor_or_vendor,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
