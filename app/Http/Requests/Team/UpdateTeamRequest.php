<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
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
            'parent_id' => ['nullable', 'exists:teams,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['nullable', 'in:regional,sector,street,support'],
            'leader_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
