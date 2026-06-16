<?php

namespace App\Http\Resources;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Faculty
 */
class FacultyResource extends JsonResource
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
            'description' => [
                'en' => $this->description_en,
                'ar' => $this->description_ar,
            ],
            'icon' => $this->icon,
            'image_url' => $this->image_url,
            'is_active' => $this->is_active,
            'programs_count' => $this->whenCounted('programs'),
            'programs' => ProgramResource::collection($this->whenLoaded('programs')),
        ];
    }
}
