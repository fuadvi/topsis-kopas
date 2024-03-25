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
        Schema::create('bobot_creteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')->nullable()->constrained('criterias');
            $table->integer('range');
            $table->integer('point');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_creteria');
    }
};
