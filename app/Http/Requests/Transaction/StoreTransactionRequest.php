<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'workspace_id' => ['required', 'exists:workspaces,id'],
            'type' => ['required', 'in:donation,expense'],
            'category' => ['nullable', 'in:cash,transfer,material,fuel,advertising,event,other'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'occurred_at' => ['required', 'date'],
            'payment_method' => ['nullable', 'in:cash,pix,transfer,card,other'],
            'donor_or_vendor' => ['nullable', 'string', 'max:255'],
        ];
    }
}
