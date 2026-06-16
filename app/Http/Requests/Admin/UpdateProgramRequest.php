<?php

namespace App\Http\Requests\Admin;

use App\Enums\DegreeLevel;
use App\Enums\ProgramLanguage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgramRequest extends FormRequest
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
            'faculty_id' => ['sometimes', 'required', 'integer', 'exists:faculties,id'],
            'degree_level' => ['sometimes', 'required', Rule::in(DegreeLevel::values())],
            'title_en' => ['sometimes', 'required', 'string', 'max:255'],
            'title_ar' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('programs', 'slug')->ignore($this->route('program'))],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'tuition_min' => ['nullable', 'numeric', 'min:0'],
            'tuition_max' => ['nullable', 'numeric', 'min:0', 'gte:tuition_min'],
            'currency' => ['nullable', 'string', 'size:3'],
            'min_admission_rate' => ['nullable', 'numeric', 'between:0,100'],
            'duration_years' => ['nullable', 'numeric', 'between:0,15'],
            'language' => ['sometimes', 'required', Rule::in(ProgramLanguage::values())],
            'highlights' => ['nullable', 'array'],
            'highlights.*' => ['string', 'max:255'],
            'university_ids' => ['nullable', 'array'],
            'university_ids.*' => ['integer', 'exists:universities,id'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}
