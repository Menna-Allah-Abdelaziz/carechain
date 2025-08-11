<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_caregiver', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('caregiver_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['patient_id', 'caregiver_id']); // لمنع التكرار
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_caregiver');
    }
};

