<?php

namespace App\Http\Requests\Voter;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVoterRequest extends FormRequest
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
            'workspace_id' => ['sometimes', 'exists:workspaces,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'cpf' => ['nullable', 'string', 'max:14'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:M,F'],
            'street' => ['nullable', 'string'],
            'number' => ['nullable', 'string'],
            'neighborhood' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'state' => ['nullable', 'string', 'size:2'],
            'zipcode' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'status' => ['nullable', 'in:supporter,undecided,opponent,unknown'],
            'origin' => ['nullable', 'in:door_to_door,event,social,import,referral'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
