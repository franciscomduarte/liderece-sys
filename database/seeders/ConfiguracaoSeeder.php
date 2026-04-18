<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Configuracao;
use Illuminate\Database\Seeder;

class ConfiguracaoSeeder extends Seeder
{
    public function run(): void
    {
        Configuracao::firstOrCreate([], [
            'nome_organizacao'         => 'Ministério da Gestão Pública',
            'escala_maxima'            => 5,
            'prazo_contestacao_dias'   => 10,
            'notif_avaliacao_pendente' => true,
            'notif_nova_avaliacao'     => true,
            'notif_relatorio_mensal'   => false,
            'auth_dois_fatores'        => false,
            'sessao_expira_minutos'    => 30,
        ]);
    }
}
