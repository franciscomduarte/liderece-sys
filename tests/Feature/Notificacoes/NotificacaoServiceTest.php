<?php

use App\Models\Area;
use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Contestacao;
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

it('notifica gestor da área quando contestação é enviada', function () {
    $area   = Area::factory()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area->id, 'status' => 'ativo']);
    $servidor = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area->id]);

    $avaliacao = Avaliacao::factory()->enviada()->create([
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
    ]);
    $contestacao = Contestacao::factory()->create([
        'avaliacao_id' => $avaliacao->id,
        'servidor_id'  => $servidor->id,
    ]);

    app(NotificacaoService::class)->contestacaoEnviada($contestacao);

    expect(Notificacao::where('destinatario_id', $gestor->id)->where('tipo', 'contestacao_enviada')->exists())->toBeTrue();
});

it('não notifica gestores de outra área quando contestação é enviada', function () {
    $area1  = Area::factory()->create();
    $area2  = Area::factory()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area2->id, 'status' => 'ativo']);
    $servidor = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area1->id]);

    $avaliacao = Avaliacao::factory()->enviada()->create([
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
    ]);
    $contestacao = Contestacao::factory()->create([
        'avaliacao_id' => $avaliacao->id,
        'servidor_id'  => $servidor->id,
    ]);

    app(NotificacaoService::class)->contestacaoEnviada($contestacao);

    expect(Notificacao::where('destinatario_id', $gestor->id)->where('tipo', 'contestacao_enviada')->exists())->toBeFalse();
});

it('notifica servidor quando contestação é respondida', function () {
    $servidor = Servidor::factory()->create(['perfil' => 'servidor']);
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
    ]);
    $contestacao = Contestacao::factory()->create([
        'avaliacao_id' => $avaliacao->id,
        'servidor_id'  => $servidor->id,
    ]);

    app(NotificacaoService::class)->contestacaoRespondida($contestacao);

    expect(Notificacao::where('destinatario_id', $servidor->id)->where('tipo', 'contestacao_respondida')->exists())->toBeTrue();
});

it('notificacao_service é disparado ao responder contestação via service', function () {
    $area     = Area::factory()->create();
    $servidor = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area->id]);
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
    ]);
    $contestacao = Contestacao::factory()->create([
        'avaliacao_id' => $avaliacao->id,
        'servidor_id'  => $servidor->id,
    ]);

    app(\App\Services\ContestacaoService::class)->responder($contestacao, 'Resposta detalhada do gestor.');

    expect(Notificacao::where('destinatario_id', $servidor->id)->where('tipo', 'contestacao_respondida')->exists())->toBeTrue();
});
