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
        Schema::create('bobot_subjects', function (Blueprint $table) {
            $table->id();
            $table->integer('bobot');
            $table->foreignId('jurusan_pnl_id')->constrained('jurusan_pnl');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_subjects');
    }
};
