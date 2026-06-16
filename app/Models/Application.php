<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Application extends Model
{
    /** @use HasFactory<ApplicationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'application_number',
        'user_id',
        'program_id',
        'preferred_university_id',
        'assigned_agent_id',
        'status',
        'source',
        'full_name',
        'email',
        'phone',
        'nationality',
        'current_education',
        'preferred_intake',
        'notes',
        'admin_notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Application $application): void {
            if (empty($application->application_number)) {
                $application->application_number = static::generateNumber();
            }
        });
    }

    public static function generateNumber(): string
    {
        do {
            $number = 'EG-'.now()->format('Y').'-'.strtoupper(Str::random(6));
        } while (static::where('application_number', $number)->exists());

        return $number;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Program, $this>
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * @return BelongsTo<University, $this>
     */
    public function preferredUniversity(): BelongsTo
    {
        return $this->belongsTo(University::class, 'preferred_university_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    /**
     * @param  Builder<Application>  $query
     */
    public function scopeStatus(Builder $query, string $status): void
    {
        $query->where('status', $status);
    }
}
