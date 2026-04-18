<?php

use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Models\ItemAvaliacao;
use App\Models\RespostaAvaliacao;
use App\Models\Servidor;
use App\Services\AvaliacaoService;

it('cria avaliação se não existir', function () {
    $ciclo = Ciclo::factory()->ativo()->create();
    $servidor = Servidor::factory()->create();
    $competencia = Competencia::factory()->create();

    $service = app(AvaliacaoService::class);
    $avaliacao = $service->obterOuCriar($ciclo, $servidor, $servidor, $competencia, 'autoavaliacao');

    expect($avaliacao->status)->toBe('rascunho');
    expect($avaliacao->tipo)->toBe('autoavaliacao');
});

it('retorna avaliação existente sem duplicar', function () {
    $ciclo = Ciclo::factory()->ativo()->create();
    $servidor = Servidor::factory()->create();
    $competencia = Competencia::factory()->create();

    $service = app(AvaliacaoService::class);
    $av1 = $service->obterOuCriar($ciclo, $servidor, $servidor, $competencia, 'autoavaliacao');
    $av2 = $service->obterOuCriar($ciclo, $servidor, $servidor, $competencia, 'autoavaliacao');

    expect($av1->id)->toBe($av2->id);
    expect(Avaliacao::count())->toBe(1);
});

it('salva respostas em rascunho', function () {
    $avaliacao = Avaliacao::factory()->create();
    $item = ItemAvaliacao::factory()->create(['competencia_id' => $avaliacao->competencia_id]);

    app(AvaliacaoService::class)->salvarRespostas($avaliacao, [$item->id => 4]);

    expect(RespostaAvaliacao::where('avaliacao_id', $avaliacao->id)->first()->nota)->toBe(4);
});

it('não permite salvar respostas em avaliação enviada', function () {
    $avaliacao = Avaliacao::factory()->enviada()->create();
    $item = ItemAvaliacao::factory()->create(['competencia_id' => $avaliacao->competencia_id]);

    expect(fn() => app(AvaliacaoService::class)->salvarRespostas($avaliacao, [$item->id => 3]))
        ->toThrow(\RuntimeException::class);
});

it('calcula média corretamente ao enviar', function () {
    $avaliacao = Avaliacao::factory()->create();
    $itens = ItemAvaliacao::factory()->count(3)->create(['competencia_id' => $avaliacao->competencia_id]);

    $notas = [
        $itens[0]->id => '4',
        $itens[1]->id => '3',
        $itens[2]->id => '5',
    ];

    app(AvaliacaoService::class)->enviar($avaliacao, $notas);

    $avaliacao->refresh();
    expect($avaliacao->status)->toBe('enviada');
    expect($avaliacao->media)->toBe(4.0);
    expect($avaliacao->enviada_at)->not()->toBeNull();
});

it('não envia avaliação com itens faltando', function () {
    $avaliacao = Avaliacao::factory()->create();
    ItemAvaliacao::factory()->count(3)->create(['competencia_id' => $avaliacao->competencia_id]);

    expect(fn() => app(AvaliacaoService::class)->enviar($avaliacao, []))
        ->toThrow(\RuntimeException::class);
});

it('não envia avaliação já enviada', function () {
    $avaliacao = Avaliacao::factory()->enviada()->create();

    expect(fn() => app(AvaliacaoService::class)->enviar($avaliacao, []))
        ->toThrow(\RuntimeException::class, 'já foi enviada');
});

it('arredonda média para 1 casa decimal', function () {
    $avaliacao = Avaliacao::factory()->create();
    $itens = ItemAvaliacao::factory()->count(3)->create(['competencia_id' => $avaliacao->competencia_id]);

    $notas = [
        $itens[0]->id => '1',
        $itens[1]->id => '2',
        $itens[2]->id => '2',
    ];

    app(AvaliacaoService::class)->enviar($avaliacao, $notas);

    $avaliacao->refresh();
    expect($avaliacao->media)->toBe(1.7);
});

it('salva comentário do gestor', function () {
    $avaliacao = Avaliacao::factory()->create();

    app(AvaliacaoService::class)->salvarComentarioGestor($avaliacao, 'Ótimo desempenho.');

    expect($avaliacao->refresh()->comentario_gestor)->toBe('Ótimo desempenho.');
});

it('não salva comentário em avaliação enviada', function () {
    $avaliacao = Avaliacao::factory()->enviada()->create();

    expect(fn() => app(AvaliacaoService::class)->salvarComentarioGestor($avaliacao, 'Texto'))
        ->toThrow(\RuntimeException::class);
});
