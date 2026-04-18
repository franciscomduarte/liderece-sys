<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contestacoes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('avaliacao_id')->unique();
            $table->uuid('servidor_id');
            $table->foreign('avaliacao_id')->references('id')->on('avaliacoes')->cascadeOnDelete();
            $table->foreign('servidor_id')->references('id')->on('servidores')->cascadeOnDelete();
            $table->text('justificativa');
            $table->text('resposta_gestor')->nullable();
            $table->enum('status', ['pendente', 'respondida', 'encerrada'])->default('pendente');
            $table->date('prazo_resposta')->nullable();
            $table->timestamp('respondida_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contestacoes');
    }
};
