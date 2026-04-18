<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    protected $table = 'configuracoes';

    protected $fillable = [
        'nome_organizacao',
        'escala_maxima',
        'prazo_contestacao_dias',
        'notif_avaliacao_pendente',
        'notif_nova_avaliacao',
        'notif_relatorio_mensal',
        'auth_dois_fatores',
        'sessao_expira_minutos',
    ];

    protected $casts = [
        'notif_avaliacao_pendente' => 'boolean',
        'notif_nova_avaliacao'     => 'boolean',
        'notif_relatorio_mensal'   => 'boolean',
        'auth_dois_fatores'        => 'boolean',
    ];

    public static function get(): self
    {
        return static::firstOrCreate([], [
            'nome_organizacao'         => 'Órgão Público Federal',
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
