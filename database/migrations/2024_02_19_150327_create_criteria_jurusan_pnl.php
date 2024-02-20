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
        Schema::create('criteria_jurusan_pnl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')->constrained('criterias');
            $table->foreignId('jurusan_pnl_id')->constrained('jurusan_pnl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria_jurusan_pnl');
    }
};
