<?php

use App\Models\Area;
use App\Models\Avaliacao;
use App\Models\Contestacao;
use App\Models\Servidor;
use Livewire\Livewire;

it('gestor vê contestações pendentes da sua área', function () {
    $area = Area::factory()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area->id]);
    $servidor = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area->id]);

    $avaliacao = Avaliacao::factory()->enviada()->create([
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
    ]);
    Contestacao::factory()->create([
        'avaliacao_id' => $avaliacao->id,
        'servidor_id'  => $servidor->id,
    ]);

    $this->actingAs($gestor->user);

    Livewire::test(\App\Livewire\Gestor\Contestacoes\Index::class)
        ->assertSee($servidor->nome);
});

it('gestor não vê contestações de outra área', function () {
    $area1 = Area::factory()->create();
    $area2 = Area::factory()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area1->id]);
    $outro = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area2->id]);

    $avaliacao = Avaliacao::factory()->enviada()->create([
        'servidor_id'  => $outro->id,
        'avaliador_id' => $outro->id,
    ]);
    Contestacao::factory()->create([
        'avaliacao_id' => $avaliacao->id,
        'servidor_id'  => $outro->id,
    ]);

    $this->actingAs($gestor->user);

    Livewire::test(\App\Livewire\Gestor\Contestacoes\Index::class)
        ->assertDontSee($outro->nome);
});

it('gestor responde contestação da sua área', function () {
    $area = Area::factory()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area->id]);
    $servidor = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area->id]);

    $avaliacao = Avaliacao::factory()->enviada()->create([
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
    ]);
    $contestacao = Contestacao::factory()->create([
        'avaliacao_id' => $avaliacao->id,
        'servidor_id'  => $servidor->id,
    ]);

    $this->actingAs($gestor->user);

    Livewire::test(\App\Livewire\Gestor\Contestacoes\Index::class)
        ->call('abrirResposta', $contestacao->id)
        ->set('resposta', 'Resposta detalhada do gestor sobre o caso apresentado.')
        ->call('responder')
        ->assertDispatched('toast');

    expect($contestacao->refresh()->status)->toBe('respondida');
});

it('gestor não pode responder contestação de outra área', function () {
    $area1 = Area::factory()->create();
    $area2 = Area::factory()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area1->id]);
    $outro = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area2->id]);

    $avaliacao = Avaliacao::factory()->enviada()->create([
        'servidor_id'  => $outro->id,
        'avaliador_id' => $outro->id,
    ]);
    $contestacao = Contestacao::factory()->create([
        'avaliacao_id' => $avaliacao->id,
        'servidor_id'  => $outro->id,
    ]);

    $this->actingAs($gestor->user);

    Livewire::test(\App\Livewire\Gestor\Contestacoes\Index::class)
        ->call('abrirResposta', $contestacao->id)
        ->set('resposta', 'Resposta indevida do gestor.')
        ->call('responder')
        ->assertForbidden();
});
