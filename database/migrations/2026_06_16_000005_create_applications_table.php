<?php

use App\Enums\ApplicationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            // Nullable: applications can be submitted by an authenticated student
            // or captured as an anonymous public lead (no account).
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->foreignId('preferred_university_id')->nullable()->constrained('universities')->nullOnDelete();
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default(ApplicationStatus::Pending->value);
            $table->string('source')->default('website');

            // Applicant snapshot (kept even if the user later edits their profile).
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('nationality')->nullable();
            $table->string('current_education')->nullable();
            $table->string('preferred_intake')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('assigned_agent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
