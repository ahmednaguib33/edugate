<?php

namespace App\Http\Resources;

use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin University
 */
class UniversityResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => [
                'en' => $this->name_en,
                'ar' => $this->name_ar,
            ],
            'slug' => $this->slug,
            'city' => $this->city,
            'description' => [
                'en' => $this->description_en,
                'ar' => $this->description_ar,
            ],
            'logo_url' => $this->logo_url,
            'website' => $this->website,
            'is_accredited' => $this->is_accredited,
            'established_year' => $this->established_year,
            'global_ranking' => $this->global_ranking,
            'is_active' => $this->is_active,
            'programs_count' => $this->whenCounted('programs'),
            'programs' => ProgramResource::collection($this->whenLoaded('programs')),
            'pivot' => $this->when(isset($this->pivot), fn () => [
                'tuition_min' => $this->pivot->tuition_min ?? null,
                'tuition_max' => $this->pivot->tuition_max ?? null,
            ]),
        ];
    }
}
