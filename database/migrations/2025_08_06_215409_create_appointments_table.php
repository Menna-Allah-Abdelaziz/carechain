<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       

// database/migrations/xxxx_xx_xx_create_appointments_table.php

Schema::create('appointments', function (Blueprint $table) {
    $table->id();
    $table->string('doctor_name');
    $table->string('family_code');
    $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');  // جديد
    $table->dateTime('appointment_time');
    $table->string('location')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
