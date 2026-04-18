<?php

use App\Models\Avaliacao;
use App\Models\ItemAvaliacao;
use App\Models\RespostaAvaliacao;
use App\Models\Servidor;
use App\Services\AvaliacaoService;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

it('admin pode reabrir avaliação enviada', function () {
    $avaliacao = Avaliacao::factory()->enviada()->create();

    app(AvaliacaoService::class)->reabrir($avaliacao);

    $avaliacao->refresh();
    expect($avaliacao->status)->toBe('rascunho');
    expect($avaliacao->media)->toBeNull();
    expect($avaliacao->enviada_at)->toBeNull();
});

it('não pode reabrir avaliação já em rascunho', function () {
    $avaliacao = Avaliacao::factory()->create(['status' => 'rascunho']);

    expect(fn() => app(AvaliacaoService::class)->reabrir($avaliacao))
        ->toThrow(\RuntimeException::class);
});

it('respostas são preservadas ao reabrir', function () {
    $avaliacao = Avaliacao::factory()->enviada()->create();
    $item = ItemAvaliacao::factory()->create(['competencia_id' => $avaliacao->competencia_id]);
    DB::table('respostas_avaliacao')->insert([
        'id'           => \Illuminate\Support\Str::uuid(),
        'avaliacao_id' => $avaliacao->id,
        'item_id'      => $item->id,
        'nota'         => 4,
        'created_at'   => now(),
        'updated_at'   => now(),
    ]);

    app(AvaliacaoService::class)->reabrir($avaliacao);

    expect(RespostaAvaliacao::where('avaliacao_id', $avaliacao->id)->count())->toBe(1);
});

it('admin pode reabrir via interface Livewire', function () {
    $admin = Servidor::factory()->admin()->create();
    $avaliacao = Avaliacao::factory()->enviada()->create();

    $this->actingAs($admin->user);

    Livewire::test(\App\Livewire\Admin\Avaliacoes\Index::class)
        ->call('confirmarReabrir', $avaliacao->id)
        ->assertSet('showConfirmModal', true)
        ->call('reabrir');

    expect($avaliacao->fresh()->status)->toBe('rascunho');
});

it('não-admin não pode reabrir avaliação', function () {
    $servidor = Servidor::factory()->create(['perfil' => 'servidor']);
    $avaliacao = Avaliacao::factory()->enviada()->create();

    $this->actingAs($servidor->user);

    Livewire::test(\App\Livewire\Admin\Avaliacoes\Index::class)
        ->call('confirmarReabrir', $avaliacao->id)
        ->call('reabrir');

    expect($avaliacao->fresh()->status)->toBe('enviada');
});
