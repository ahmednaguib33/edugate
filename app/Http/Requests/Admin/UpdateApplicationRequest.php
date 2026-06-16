<?php

namespace App\Http\Requests\Admin;

use App\Enums\ApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'required', Rule::in(ApplicationStatus::values())],
            'assigned_agent_id' => ['nullable', 'integer', 'exists:users,id'],
            'preferred_university_id' => ['nullable', 'integer', 'exists:universities,id'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
