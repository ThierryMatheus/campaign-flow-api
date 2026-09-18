<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'workspace_id' => ['sometimes', 'exists:workspaces,id'],
            'type' => ['sometimes', 'in:donation,expense'],
            'category' => ['nullable', 'in:cash,transfer,material,fuel,advertising,event,other'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['sometimes', 'numeric', 'min:0.01'],
            'occurred_at' => ['sometimes', 'date'],
            'payment_method' => ['nullable', 'in:cash,pix,transfer,card,other'],
            'donor_or_vendor' => ['nullable', 'string', 'max:255'],
        ];
    }
}
