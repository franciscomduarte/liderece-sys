<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ciclo;
use App\Models\Servidor;
use Illuminate\Database\Seeder;

class CicloSeeder extends Seeder
{
    public function run(): void
    {
        if (Ciclo::where('status', 'ativo')->exists()) {
            return;
        }

        $admin = Servidor::where('perfil', 'admin')->first();

        Ciclo::create([
            'nome'                     => 'Ciclo 2024.1',
            'data_inicio'              => '2024-03-01',
            'data_fim'                 => '2024-06-30',
            'status'                   => 'ativo',
            'prazo_contestacao_dias'   => 10,
            'created_by'               => $admin?->id,
        ]);
    }
}
