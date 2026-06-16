<?php

use App\Enums\DegreeLevel;
use App\Enums\ProgramLanguage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('faculties')->cascadeOnDelete();
            $table->string('degree_level')->default(DegreeLevel::Bachelor->value);
            $table->string('title_en');
            $table->string('title_ar');
            $table->string('slug')->unique();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->decimal('tuition_min', 10, 2)->nullable();
            $table->decimal('tuition_max', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('min_admission_rate', 5, 2)->nullable();
            $table->decimal('duration_years', 3, 1)->nullable();
            $table->string('language')->default(ProgramLanguage::Both->value);
            $table->json('highlights')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['degree_level', 'is_active']);
            $table->index('faculty_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
