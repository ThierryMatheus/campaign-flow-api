<?php

namespace App\Http\Requests\Demand;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDemandRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'workspace_id' => ['sometimes', 'exists:workspaces,id'],
            'voter_id' => ['nullable', 'exists:voters,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:open,in_progress,resolved,closed,cancelled'],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
            'category' => ['nullable', 'string', 'max:50'],
        ];
    }
}
