<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_university', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->cascadeOnDelete();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->decimal('tuition_min', 10, 2)->nullable();
            $table->decimal('tuition_max', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['program_id', 'university_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_university');
    }
};
