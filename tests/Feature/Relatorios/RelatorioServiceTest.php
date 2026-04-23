<?php

use App\Models\Area;
use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Servidor;
use App\Services\RelatorioService;

it('resumo geral conta avaliações e calcula percentual', function () {
    $ciclo    = Ciclo::factory()->ativo()->create();
    $area     = Area::factory()->create();
    $servidor = Servidor::factory()->create(['area_id' => $area->id]);

    Avaliacao::factory()->enviada()->create(['ciclo_id' => $ciclo->id, 'servidor_id' => $servidor->id, 'avaliador_id' => $servidor->id, 'tipo' => 'area']);
    Avaliacao::factory()->create(['ciclo_id' => $ciclo->id, 'servidor_id' => $servidor->id, 'avaliador_id' => $servidor->id, 'tipo' => 'area', 'status' => 'rascunho']);

    $resumo = app(RelatorioService::class)->resumoGeral($ciclo);

    expect($resumo['total_avaliacoes'])->toBe(2);
    expect($resumo['enviadas'])->toBe(1);
    expect($resumo['percentual_concluido'])->toBe(50);
});

it('media por area retorna média correta por área', function () {
    $ciclo = Ciclo::factory()->ativo()->create();
    $area  = Area::factory()->create(['nome' => 'TI Teste']);
    $servidor = Servidor::factory()->create(['area_id' => $area->id]);

    Avaliacao::factory()->enviada()->create([
        'ciclo_id' => $ciclo->id, 'servidor_id' => $servidor->id,
        'avaliador_id' => $servidor->id, 'tipo' => 'area', 'media' => 4.0,
    ]);

    $porArea = app(RelatorioService::class)->mediaPorArea($ciclo);

    expect($porArea)->not->toBeEmpty();
    $entry = collect($porArea)->firstWhere('nome', 'TI Teste');
    expect($entry)->not->toBeNull();
    expect($entry['media'])->toBe(4.0);
});

it('distribuicao status conta rascunhos e enviadas separadamente', function () {
    $ciclo    = Ciclo::factory()->ativo()->create();
    $servidor = Servidor::factory()->create();

    Avaliacao::factory()->enviada()->create(['ciclo_id' => $ciclo->id, 'servidor_id' => $servidor->id, 'avaliador_id' => $servidor->id, 'tipo' => 'area']);
    Avaliacao::factory()->create(['ciclo_id' => $ciclo->id, 'servidor_id' => $servidor->id, 'avaliador_id' => $servidor->id, 'tipo' => 'area', 'status' => 'rascunho']);
    Avaliacao::factory()->create(['ciclo_id' => $ciclo->id, 'servidor_id' => $servidor->id, 'avaliador_id' => $servidor->id, 'tipo' => 'area', 'status' => 'rascunho']);

    $status = app(RelatorioService::class)->distribuicaoStatus($ciclo);

    expect($status['enviada'])->toBe(1);
    expect($status['rascunho'])->toBe(2);
});

it('ranking de servidores ordena por media decrescente', function () {
    $ciclo = Ciclo::factory()->ativo()->create();
    $s1    = Servidor::factory()->create();
    $s2    = Servidor::factory()->create();

    Avaliacao::factory()->enviada()->create(['ciclo_id' => $ciclo->id, 'servidor_id' => $s1->id, 'avaliador_id' => $s1->id, 'tipo' => 'area', 'media' => 4.5]);
    Avaliacao::factory()->enviada()->create(['ciclo_id' => $ciclo->id, 'servidor_id' => $s2->id, 'avaliador_id' => $s2->id, 'tipo' => 'area', 'media' => 3.0]);

    $ranking = app(RelatorioService::class)->rankingServidores($ciclo);

    expect($ranking[0]['nome'])->toBe($s1->nome);
    expect($ranking[1]['nome'])->toBe($s2->nome);
});

it('resumo area restringe dados à área do gestor', function () {
    $ciclo = Ciclo::factory()->ativo()->create();
    $area1 = Area::factory()->create();
    $area2 = Area::factory()->create();
    $s1    = Servidor::factory()->create(['area_id' => $area1->id]);
    $s2    = Servidor::factory()->create(['area_id' => $area2->id]);

    Avaliacao::factory()->enviada()->create(['ciclo_id' => $ciclo->id, 'servidor_id' => $s1->id, 'avaliador_id' => $s1->id, 'tipo' => 'area']);
    Avaliacao::factory()->enviada()->create(['ciclo_id' => $ciclo->id, 'servidor_id' => $s2->id, 'avaliador_id' => $s2->id, 'tipo' => 'area']);

    $resumo = app(RelatorioService::class)->resumoArea($ciclo, $area1);

    expect($resumo['enviadas'])->toBe(1);
    expect($resumo['total_servidores'])->toBe(1);
});

it('servidores da area retorna apenas servidores da area', function () {
    $ciclo = Ciclo::factory()->ativo()->create();
    $area1 = Area::factory()->create();
    $area2 = Area::factory()->create();
    Servidor::factory()->create(['area_id' => $area1->id, 'perfil' => 'servidor', 'status' => 'ativo']);
    Servidor::factory()->create(['area_id' => $area2->id, 'perfil' => 'servidor', 'status' => 'ativo']);

    $servidores = app(RelatorioService::class)->servidoresDaArea($ciclo, $area1);

    expect($servidores)->toHaveCount(1);
});

it('historico servidor agrupa por ciclo', function () {
    $s = Servidor::factory()->create();

    $ciclo1 = Ciclo::factory()->create(['status' => 'inativo']);
    $ciclo2 = Ciclo::factory()->ativo()->create();

    Avaliacao::factory()->enviada()->create(['ciclo_id' => $ciclo1->id, 'servidor_id' => $s->id, 'avaliador_id' => $s->id, 'tipo' => 'area']);
    Avaliacao::factory()->enviada()->create(['ciclo_id' => $ciclo2->id, 'servidor_id' => $s->id, 'avaliador_id' => $s->id, 'tipo' => 'area']);

    $historico = app(RelatorioService::class)->historicoServidor($s);

    expect($historico)->toHaveCount(2);
});

it('historico servidor ignora autoavaliacoes', function () {
    $s     = Servidor::factory()->create();
    $ciclo = Ciclo::factory()->ativo()->create();

    Avaliacao::factory()->enviada()->create(['ciclo_id' => $ciclo->id, 'servidor_id' => $s->id, 'avaliador_id' => $s->id, 'tipo' => 'autoavaliacao']);

    $historico = app(RelatorioService::class)->historicoServidor($s);

    expect($historico)->toBeEmpty();
});
