<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respostas_avaliacao', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('avaliacao_id');
            $table->uuid('item_id');
            $table->foreign('avaliacao_id')->references('id')->on('avaliacoes')->cascadeOnDelete();
            $table->foreign('item_id')->references('id')->on('itens_avaliacao')->cascadeOnDelete();
            $table->integer('nota');
            $table->unique(['avaliacao_id', 'item_id']);
            $table->timestamps();
        });

        if (\DB::connection()->getDriverName() === 'pgsql') {
            \DB::statement('ALTER TABLE respostas_avaliacao ADD CONSTRAINT nota_range CHECK (nota >= 1 AND nota <= 5)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('respostas_avaliacao');
    }
};
