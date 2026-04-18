<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competencias_areas', function (Blueprint $table) {
            $table->uuid('competencia_id');
            $table->uuid('area_id');
            $table->foreign('competencia_id')->references('id')->on('competencias')->cascadeOnDelete();
            $table->foreign('area_id')->references('id')->on('areas')->cascadeOnDelete();
            $table->primary(['competencia_id', 'area_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competencias_areas');
    }
};
