<?php

use App\Models\Area;
use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Models\Servidor;
use App\Services\GapService;

// ── Helpers estáticos ─────────────────────────────────────────────────────────

it('descricaoNivel retorna os rótulos corretos', function () {
    expect(GapService::descricaoNivel(1))->toBe('Inicial');
    expect(GapService::descricaoNivel(2))->toBe('Básico');
    expect(GapService::descricaoNivel(3))->toBe('Proficiente');
    expect(GapService::descricaoNivel(4))->toBe('Avançado');
    expect(GapService::descricaoNivel(5))->toBe('Referência');
});

it('classificar retorna adequado quando gap é zero', function () {
    expect(GapService::classificar(0))->toBe('adequado');
});

it('classificar retorna leve quando gap é 1', function () {
    expect(GapService::classificar(1))->toBe('leve');
});

it('classificar retorna estrategico quando gap >= 2', function () {
    expect(GapService::classificar(2))->toBe('estrategico');
    expect(GapService::classificar(4))->toBe('estrategico');
});

// ── gapsDoServidor ────────────────────────────────────────────────────────────

it('retorna gap zero quando nivel_atual iguala nivel_esperado', function () {
    $area       = Area::factory()->create();
    $ciclo      = Ciclo::factory()->ativo()->create();
    $servidor   = Servidor::factory()->create(['area_id' => $area->id, 'perfil' => 'servidor']);
    $competencia= Competencia::factory()->create();
    $area->competencias()->attach($competencia->id, ['nivel_esperado' => 3]);

    // Avaliação com média 3.5 → nivel_atual = 3
    Avaliacao::factory()->enviada()->create([
        'ciclo_id'       => $ciclo->id,
        'servidor_id'    => $servidor->id,
        'competencia_id' => $competencia->id,
        'tipo'           => 'area',
        'media'          => 3.5,
    ]);

    $gaps = app(GapService::class)->gapsDoServidor($servidor, $ciclo);

    expect($gaps)->toHaveCount(1);
    expect($gaps->first()['gap'])->toBe(0);
    expect($gaps->first()['classificacao'])->toBe('adequado');
});

it('retorna gap 1 quando nivel_esperado supera nivel_atual em 1', function () {
    $area       = Area::factory()->create();
    $ciclo      = Ciclo::factory()->ativo()->create();
    $servidor   = Servidor::factory()->create(['area_id' => $area->id, 'perfil' => 'servidor']);
    $competencia= Competencia::factory()->create();
    $area->competencias()->attach($competencia->id, ['nivel_esperado' => 4]);

    Avaliacao::factory()->enviada()->create([
        'ciclo_id'       => $ciclo->id,
        'servidor_id'    => $servidor->id,
        'competencia_id' => $competencia->id,
        'tipo'           => 'area',
        'media'          => 3.2, // nivel_atual = 3
    ]);

    $item = app(GapService::class)->gapsDoServidor($servidor, $ciclo)->first();

    expect($item['gap'])->toBe(1);
    expect($item['classificacao'])->toBe('leve');
});

it('retorna gap estrategico quando diferenca >= 2', function () {
    $area       = Area::factory()->create();
    $ciclo      = Ciclo::factory()->ativo()->create();
    $servidor   = Servidor::factory()->create(['area_id' => $area->id, 'perfil' => 'servidor']);
    $competencia= Competencia::factory()->create();
    $area->competencias()->attach($competencia->id, ['nivel_esperado' => 5]);

    Avaliacao::factory()->enviada()->create([
        'ciclo_id'       => $ciclo->id,
        'servidor_id'    => $servidor->id,
        'competencia_id' => $competencia->id,
        'tipo'           => 'area',
        'media'          => 1.5, // nivel_atual = 1
    ]);

    $item = app(GapService::class)->gapsDoServidor($servidor, $ciclo)->first();

    expect($item['gap'])->toBe(4);
    expect($item['classificacao'])->toBe('estrategico');
});

it('retorna gap nulo quando servidor ainda nao foi avaliado', function () {
    $area       = Area::factory()->create();
    $ciclo      = Ciclo::factory()->ativo()->create();
    $servidor   = Servidor::factory()->create(['area_id' => $area->id, 'perfil' => 'servidor']);
    $competencia= Competencia::factory()->create();
    $area->competencias()->attach($competencia->id, ['nivel_esperado' => 3]);

    $item = app(GapService::class)->gapsDoServidor($servidor, $ciclo)->first();

    expect($item['gap'])->toBeNull();
    expect($item['classificacao'])->toBeNull();
});

it('gap nunca é negativo quando nivel_atual supera nivel_esperado', function () {
    $area       = Area::factory()->create();
    $ciclo      = Ciclo::factory()->ativo()->create();
    $servidor   = Servidor::factory()->create(['area_id' => $area->id, 'perfil' => 'servidor']);
    $competencia= Competencia::factory()->create();
    $area->competencias()->attach($competencia->id, ['nivel_esperado' => 2]);

    Avaliacao::factory()->enviada()->create([
        'ciclo_id'       => $ciclo->id,
        'servidor_id'    => $servidor->id,
        'competencia_id' => $competencia->id,
        'tipo'           => 'area',
        'media'          => 4.8, // nivel_atual = 5
    ]);

    $item = app(GapService::class)->gapsDoServidor($servidor, $ciclo)->first();

    expect($item['gap'])->toBe(0);
    expect($item['classificacao'])->toBe('adequado');
});

it('retorna colecao vazia para servidor sem area', function () {
    $ciclo    = Ciclo::factory()->ativo()->create();
    $servidor = Servidor::factory()->create(['area_id' => null]);

    $gaps = app(GapService::class)->gapsDoServidor($servidor, $ciclo);

    expect($gaps)->toBeEmpty();
});

// ── resumirGaps ───────────────────────────────────────────────────────────────

it('resumirGaps contabiliza classificacoes corretamente', function () {
    $gaps = collect([
        ['classificacao' => 'adequado'],
        ['classificacao' => 'adequado'],
        ['classificacao' => 'leve'],
        ['classificacao' => 'estrategico'],
        ['classificacao' => null],
    ]);

    $resumo = app(GapService::class)->resumirGaps($gaps);

    expect($resumo['adequado'])->toBe(2);
    expect($resumo['leve'])->toBe(1);
    expect($resumo['estrategico'])->toBe(1);
    expect($resumo['sem_avaliacao'])->toBe(1);
});
