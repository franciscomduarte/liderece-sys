<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ciclos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->enum('status', ['ativo', 'inativo'])->default('inativo');
            $table->integer('prazo_contestacao_dias')->default(10);
            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('servidores')->nullOnDelete();
            $table->timestamps();
        });

        // Apenas 1 ciclo ativo por vez (partial index — apenas PostgreSQL; validação adicional no CicloService)
        if (\DB::connection()->getDriverName() === 'pgsql') {
            \DB::statement("CREATE UNIQUE INDEX ciclo_ativo_unico ON ciclos (status) WHERE status = 'ativo'");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ciclos');
    }
};
