<?php

namespace App\Http\Resources;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Program
 */
class ProgramResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => [
                'en' => $this->title_en,
                'ar' => $this->title_ar,
            ],
            'slug' => $this->slug,
            'degree_level' => [
                'value' => $this->degree_level?->value,
                'en' => $this->degree_level?->labelEn(),
                'ar' => $this->degree_level?->labelAr(),
            ],
            'description' => [
                'en' => $this->description_en,
                'ar' => $this->description_ar,
            ],
            'tuition' => [
                'min' => $this->tuition_min !== null ? (float) $this->tuition_min : null,
                'max' => $this->tuition_max !== null ? (float) $this->tuition_max : null,
                'currency' => $this->currency,
            ],
            'min_admission_rate' => $this->min_admission_rate !== null ? (float) $this->min_admission_rate : null,
            'duration_years' => $this->duration_years !== null ? (float) $this->duration_years : null,
            'language' => [
                'value' => $this->language?->value,
                'en' => $this->language?->labelEn(),
                'ar' => $this->language?->labelAr(),
            ],
            'highlights' => $this->highlights ?? [],
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'faculty' => new FacultyResource($this->whenLoaded('faculty')),
            'universities' => UniversityResource::collection($this->whenLoaded('universities')),
            'universities_count' => $this->whenCounted('universities'),
            'created_at' => $this->created_at,
        ];
    }
}
