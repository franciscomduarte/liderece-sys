<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_organizacao')->default('Órgão Público Federal');
            $table->integer('escala_maxima')->default(5);
            $table->integer('prazo_contestacao_dias')->default(10);
            $table->boolean('notif_avaliacao_pendente')->default(true);
            $table->boolean('notif_nova_avaliacao')->default(true);
            $table->boolean('notif_relatorio_mensal')->default(false);
            $table->boolean('auth_dois_fatores')->default(false);
            $table->integer('sessao_expira_minutos')->default(30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracoes');
    }
};
