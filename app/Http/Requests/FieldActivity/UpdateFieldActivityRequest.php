<?php

namespace App\Http\Requests\FieldActivity;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'workspace_id' => ['sometimes', 'exists:workspaces,id'],
            'voter_id' => ['nullable', 'exists:voters,id'],
            'team_id' => ['nullable', 'exists:teams,id'],
            'type' => ['sometimes', 'in:visit,call,event,distribution,other'],
            'result' => ['nullable', 'in:positive,neutral,negative,not_home,refused'],
            'notes' => ['nullable', 'string'],
            'performed_at' => ['sometimes', 'date'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ];
    }
}
