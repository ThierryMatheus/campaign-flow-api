<?php

namespace App\Http\Requests\Team;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'workspace_id' => ['required', 'exists:workspaces,id'],
            'parent_id' => ['nullable', 'exists:teams,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:regional,sector,street,support'],
            'leader_id' => ['nullable', 'exists:users,id']
        ];
    }
}
