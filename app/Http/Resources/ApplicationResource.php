<?php

namespace App\Http\Resources;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Application
 */
class ApplicationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_number' => $this->application_number,
            'status' => [
                'value' => $this->status?->value,
                'label' => $this->status?->label(),
            ],
            'source' => $this->source,
            'applicant' => [
                'full_name' => $this->full_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'nationality' => $this->nationality,
                'current_education' => $this->current_education,
            ],
            'preferred_intake' => $this->preferred_intake,
            'notes' => $this->notes,
            'admin_notes' => $this->when(
                $request->user()?->isStaff() ?? false,
                $this->admin_notes
            ),
            'program' => new ProgramResource($this->whenLoaded('program')),
            'preferred_university' => new UniversityResource($this->whenLoaded('preferredUniversity')),
            'student' => new UserResource($this->whenLoaded('student')),
            'assigned_agent' => new UserResource($this->whenLoaded('assignedAgent')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
