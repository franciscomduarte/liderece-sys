<?php

use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Contestacao;
use App\Models\Servidor;
use Livewire\Livewire;

it('servidor vê botão contestar quando dentro do prazo', function () {
    $ciclo = Ciclo::factory()->ativo()->create(['prazo_contestacao_dias' => 10]);
    $servidor = Servidor::factory()->create(['perfil' => 'servidor']);
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'ciclo_id'     => $ciclo->id,
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
        'enviada_at'   => now(),
    ]);

    $this->actingAs($servidor->user);

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Resultado::class, ['avaliacao' => $avaliacao])
        ->assertSee('Contestar');
});

it('servidor não vê botão contestar após o prazo', function () {
    $ciclo = Ciclo::factory()->ativo()->create(['prazo_contestacao_dias' => 3]);
    $servidor = Servidor::factory()->create(['perfil' => 'servidor']);
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'ciclo_id'     => $ciclo->id,
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
        'enviada_at'   => now()->subDays(10),
    ]);

    $this->actingAs($servidor->user);

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Resultado::class, ['avaliacao' => $avaliacao])
        ->assertSee('Prazo de contestação encerrado.')
        ->assertDontSee('Contestação disponível');
});

it('servidor envia contestação via modal', function () {
    $ciclo = Ciclo::factory()->ativo()->create(['prazo_contestacao_dias' => 10]);
    $servidor = Servidor::factory()->create(['perfil' => 'servidor']);
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'ciclo_id'     => $ciclo->id,
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
        'enviada_at'   => now(),
    ]);

    $this->actingAs($servidor->user);

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Resultado::class, ['avaliacao' => $avaliacao])
        ->set('justificativa', 'Esta avaliação não reflete meu desempenho real, pois os critérios não foram aplicados corretamente.')
        ->call('contestar')
        ->assertDispatched('toast');

    expect(Contestacao::where('avaliacao_id', $avaliacao->id)->exists())->toBeTrue();
});

it('servidor não pode contestar sem justificativa suficiente', function () {
    $ciclo = Ciclo::factory()->ativo()->create(['prazo_contestacao_dias' => 10]);
    $servidor = Servidor::factory()->create(['perfil' => 'servidor']);
    $avaliacao = Avaliacao::factory()->enviada()->create([
        'ciclo_id'     => $ciclo->id,
        'servidor_id'  => $servidor->id,
        'avaliador_id' => $servidor->id,
        'enviada_at'   => now(),
    ]);

    $this->actingAs($servidor->user);

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Resultado::class, ['avaliacao' => $avaliacao])
        ->set('justificativa', 'Curta')
        ->call('contestar')
        ->assertHasErrors(['justificativa']);
});

it('servidor vê contestação já enviada na tela de resultado', function () {
    $avaliacao = Avaliacao::factory()->enviada()->create();
    $servidor = $avaliacao->servidor;
    $contestacao = Contestacao::factory()->create([
        'avaliacao_id' => $avaliacao->id,
        'servidor_id'  => $servidor->id,
    ]);

    $this->actingAs($servidor->user);

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Resultado::class, ['avaliacao' => $avaliacao])
        ->assertSee('Contestação')
        ->assertDontSee('Contestação disponível');
});
