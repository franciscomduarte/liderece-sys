<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ciclo_id');
            $table->uuid('servidor_id');
            $table->uuid('avaliador_id');
            $table->uuid('competencia_id');
            $table->foreign('ciclo_id')->references('id')->on('ciclos')->cascadeOnDelete();
            $table->foreign('servidor_id')->references('id')->on('servidores')->cascadeOnDelete();
            $table->foreign('avaliador_id')->references('id')->on('servidores')->cascadeOnDelete();
            $table->foreign('competencia_id')->references('id')->on('competencias')->cascadeOnDelete();
            $table->enum('tipo', ['autoavaliacao', 'area']);
            $table->decimal('media', 3, 1)->nullable();
            $table->text('comentario_gestor')->nullable();
            $table->enum('status', ['rascunho', 'enviada'])->default('rascunho');
            $table->timestamp('enviada_at')->nullable();
            $table->timestamps();
            $table->unique(['ciclo_id', 'servidor_id', 'avaliador_id', 'competencia_id', 'tipo'], 'uq_avaliacao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
