<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itens_avaliacao', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('competencia_id');
            $table->foreign('competencia_id')->references('id')->on('competencias')->cascadeOnDelete();
            $table->text('descricao');
            $table->integer('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itens_avaliacao');
    }
};
