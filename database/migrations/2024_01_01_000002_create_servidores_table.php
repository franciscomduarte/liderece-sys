<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servidores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('matricula')->unique();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('cargo');
            $table->uuid('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas')->nullOnDelete();
            $table->enum('perfil', ['admin', 'gestor', 'servidor'])->default('servidor');
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->boolean('primeiro_acesso')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servidores');
    }
};
