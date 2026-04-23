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
        Schema::table('servidores', function (Blueprint $table) {
            $table->date('data_nascimento')->nullable()->after('cargo');
            $table->date('data_ingresso')->nullable()->after('data_nascimento');
            $table->string('escolaridade', 50)->nullable()->after('data_ingresso');
            $table->string('genero', 30)->nullable()->after('escolaridade');
            $table->string('raca', 30)->nullable()->after('genero');
        });
    }

    public function down(): void
    {
        Schema::table('servidores', function (Blueprint $table) {
            $table->dropColumn(['data_nascimento', 'data_ingresso', 'escolaridade', 'genero', 'raca']);
        });
    }
};
