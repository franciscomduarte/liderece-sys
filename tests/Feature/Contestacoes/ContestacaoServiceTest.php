<?php

use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Contestacao;
use App\Models\Servidor;
use App\Services\ContestacaoService;

it('servidor pode contestar avaliação enviada dentro do prazo', function () {
    $ciclo = Ciclo::factory()->ativo()->create(['prazo_contestacao_dias' => 10]);
    $servidor = Servidor::factory()->create();
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'ciclo_id'    => $ciclo->id,
        'servidor_id' => $servidor->id,
        'avaliador_id' => $servidor->id,
        'enviada_at'  => now(),
    ]);

    $service = app(ContestacaoService::class);

    expect($service->podeContestar($avaliacao, $servidor))->toBeTrue();
});

it('servidor não pode contestar após o prazo', function () {
    $ciclo = Ciclo::factory()->ativo()->create(['prazo_contestacao_dias' => 5]);
    $servidor = Servidor::factory()->create();
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'ciclo_id'    => $ciclo->id,
        'servidor_id' => $servidor->id,
        'avaliador_id' => $servidor->id,
        'enviada_at'  => now()->subDays(10),
    ]);

    expect(app(ContestacaoService::class)->podeContestar($avaliacao, $servidor))->toBeFalse();
});

it('servidor não pode contestar avaliação não enviada', function () {
    $avaliacao = Avaliacao::factory()->create();
    $servidor = $avaliacao->servidor;

    expect(app(ContestacaoService::class)->podeContestar($avaliacao, $servidor))->toBeFalse();
});

it('servidor só pode contestar uma vez', function () {
    $ciclo = Ciclo::factory()->ativo()->create(['prazo_contestacao_dias' => 10]);
    $servidor = Servidor::factory()->create();
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'ciclo_id'    => $ciclo->id,
        'servidor_id' => $servidor->id,
        'avaliador_id' => $servidor->id,
        'enviada_at'  => now(),
    ]);

    $service = app(ContestacaoService::class);
    $service->contestar($avaliacao, $servidor, 'Motivo da contestação com texto suficiente.');

    expect($service->podeContestar($avaliacao, $servidor))->toBeFalse();
});

it('cria contestação com prazo calculado', function () {
    $ciclo = Ciclo::factory()->ativo()->create(['prazo_contestacao_dias' => 7]);
    $servidor = Servidor::factory()->create();
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'ciclo_id'    => $ciclo->id,
        'servidor_id' => $servidor->id,
        'avaliador_id' => $servidor->id,
        'enviada_at'  => now(),
    ]);

    $contestacao = app(ContestacaoService::class)->contestar($avaliacao, $servidor, 'Justificativa detalhada com mais de 20 caracteres.');

    expect($contestacao->status)->toBe('pendente');
    expect($contestacao->prazo_resposta->diffInDays(now()))->toBeLessThanOrEqual(7);
});

it('gestor responde contestação pendente', function () {
    $contestacao = Contestacao::factory()->create();

    app(ContestacaoService::class)->responder($contestacao, 'Resposta detalhada do gestor.');

    $contestacao->refresh();
    expect($contestacao->status)->toBe('respondida');
    expect($contestacao->resposta_gestor)->toBe('Resposta detalhada do gestor.');
    expect($contestacao->respondida_at)->not()->toBeNull();
});

it('não permite responder contestação já respondida', function () {
    $contestacao = Contestacao::factory()->respondida()->create();

    expect(fn() => app(ContestacaoService::class)->responder($contestacao, 'Nova resposta'))
        ->toThrow(\RuntimeException::class);
});

it('encerra contestações vencidas', function () {
    Contestacao::factory()->create(['prazo_resposta' => now()->subDays(2)->toDateString()]);
    Contestacao::factory()->create(['prazo_resposta' => now()->addDays(5)->toDateString()]);

    $encerradas = app(ContestacaoService::class)->encerrarVencidas();

    expect($encerradas)->toBe(1);
    expect(Contestacao::where('status', 'encerrada')->count())->toBe(1);
    expect(Contestacao::where('status', 'pendente')->count())->toBe(1);
});

it('não pode contestar avaliação de outro servidor', function () {
    $ciclo = Ciclo::factory()->ativo()->create(['prazo_contestacao_dias' => 10]);
    $servidor1 = Servidor::factory()->create();
    $servidor2 = Servidor::factory()->create();
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'ciclo_id'    => $ciclo->id,
        'servidor_id' => $servidor1->id,
        'avaliador_id' => $servidor1->id,
        'enviada_at'  => now(),
    ]);

    expect(app(ContestacaoService::class)->podeContestar($avaliacao, $servidor2))->toBeFalse();
});
