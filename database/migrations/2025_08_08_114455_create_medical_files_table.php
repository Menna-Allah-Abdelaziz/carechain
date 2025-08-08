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
        Schema::create('medical_files', function (Blueprint $table) {
    $table->id();
    $table->string('file_path'); // مكان الصورة أو الملف
    $table->text('note')->nullable(); // الملاحظة
    $table->string('file_type')->nullable(); // نوعه تحليل دم، أشعة، ..الخ
    $table->string('family_code'); // نربطه بالفاميلي كود (string عادي)
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_files');
    }
};
