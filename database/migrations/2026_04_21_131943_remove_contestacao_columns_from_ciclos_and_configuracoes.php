<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ciclos', function (Blueprint $table) {
            if (Schema::hasColumn('ciclos', 'prazo_contestacao_dias')) {
                $table->dropColumn('prazo_contestacao_dias');
            }
        });

        Schema::table('configuracoes', function (Blueprint $table) {
            if (Schema::hasColumn('configuracoes', 'prazo_contestacao_dias')) {
                $table->dropColumn('prazo_contestacao_dias');
            }
        });

        Schema::dropIfExists('contestacoes');
    }

    public function down(): void
    {
        Schema::table('ciclos', function (Blueprint $table) {
            $table->integer('prazo_contestacao_dias')->default(10)->after('status');
        });

        Schema::table('configuracoes', function (Blueprint $table) {
            $table->integer('prazo_contestacao_dias')->default(10);
        });
    }
};
