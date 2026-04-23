<?php

use App\Models\Area;
use App\Models\Avaliacao;
use App\Models\Notificacao;
use App\Models\Servidor;
use App\Services\NotificacaoService;

it('cria notificação quando gestor envia avaliação do tipo gestor', function () {
    $area     = Area::factory()->create();
    $servidor = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area->id]);
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
        'tipo'         => 'area',
    ]);

    app(NotificacaoService::class)->avaliacaoEnviada($avaliacao);

    expect(Notificacao::where('destinatario_id', $servidor->id)->where('tipo', 'avaliacao_enviada')->exists())->toBeTrue();
});

it('não cria notificação para autoavaliação enviada', function () {
    $servidor = Servidor::factory()->create();
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
        'tipo'         => 'autoavaliacao',
    ]);

    app(NotificacaoService::class)->avaliacaoEnviada($avaliacao);

    expect(Notificacao::where('destinatario_id', $servidor->id)->where('tipo', 'avaliacao_enviada')->exists())->toBeFalse();
});
