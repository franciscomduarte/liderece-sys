<?php

use App\Livewire\Admin\Configuracoes\Ciclos;
use App\Models\Ciclo;
use App\Models\Servidor;
use App\Services\CicloService;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = Servidor::factory()->admin()->create();
    $this->actingAs($this->admin->user);
});

it('exibe listagem de ciclos', function () {
    $ciclo = Ciclo::factory()->create(['created_by' => $this->admin->id]);

    Livewire::test(Ciclos::class)
        ->assertStatus(200)
        ->assertSee($ciclo->nome);
});

it('cria um novo ciclo como inativo', function () {
    Livewire::test(Ciclos::class)
        ->call('openCreate')
        ->set('nome', 'Ciclo 2025/1')
        ->set('data_inicio', '2025-01-01')
        ->set('data_fim', '2025-06-30')
        ->call('save')
        ->assertDispatched('toast');

    $ciclo = Ciclo::where('nome', 'Ciclo 2025/1')->first();
    expect($ciclo)->not->toBeNull()
        ->and($ciclo->status)->toBe('inativo');
});

it('valida data_fim posterior a data_inicio', function () {
    Livewire::test(Ciclos::class)
        ->call('openCreate')
        ->set('nome', 'Ciclo Inválido')
        ->set('data_inicio', '2025-06-01')
        ->set('data_fim', '2025-01-01')
        ->call('save')
        ->assertHasErrors(['data_fim']);
});

it('ativa um ciclo inativo', function () {
    $ciclo = Ciclo::factory()->create(['status' => 'inativo', 'created_by' => $this->admin->id]);

    Livewire::test(Ciclos::class)->call('ativar', $ciclo->id);

    expect($ciclo->fresh()->status)->toBe('ativo');
});

it('não ativa ciclo quando já existe outro ativo', function () {
    Ciclo::factory()->create(['status' => 'ativo', 'created_by' => $this->admin->id]);
    $outro = Ciclo::factory()->create(['status' => 'inativo', 'created_by' => $this->admin->id]);

    Livewire::test(Ciclos::class)
        ->call('ativar', $outro->id)
        ->assertDispatched('toast');

    expect($outro->fresh()->status)->toBe('inativo');
});

it('desativa um ciclo ativo', function () {
    $ciclo = Ciclo::factory()->create(['status' => 'ativo', 'created_by' => $this->admin->id]);

    Livewire::test(Ciclos::class)->call('desativar', $ciclo->id);

    expect($ciclo->fresh()->status)->toBe('inativo');
});

it('não pode criar novo ciclo quando já existe ativo', function () {
    Ciclo::factory()->create(['status' => 'ativo', 'created_by' => $this->admin->id]);

    Livewire::test(Ciclos::class)
        ->call('openCreate')
        ->set('nome', 'Segundo Ciclo')
        ->set('data_inicio', '2025-07-01')
        ->set('data_fim', '2025-12-31')
        ->call('save')
        ->assertDispatched('toast');

    expect(Ciclo::count())->toBe(1);
});

it('exclui ciclo inativo sem avaliações', function () {
    $ciclo = Ciclo::factory()->create(['status' => 'inativo', 'created_by' => $this->admin->id]);

    Livewire::test(Ciclos::class)
        ->call('confirmDelete', $ciclo->id)
        ->call('delete');

    expect(Ciclo::find($ciclo->id))->toBeNull();
});

it('CicloService lança exceção ao ativar com ciclo já ativo', function () {
    Ciclo::factory()->create(['status' => 'ativo', 'created_by' => $this->admin->id]);
    $outro = Ciclo::factory()->create(['status' => 'inativo', 'created_by' => $this->admin->id]);

    expect(fn () => app(CicloService::class)->ativar($outro))
        ->toThrow(\RuntimeException::class);
});

it('CicloService::desativar muda status para inativo', function () {
    $ciclo = Ciclo::factory()->create(['status' => 'ativo', 'created_by' => $this->admin->id]);
    app(CicloService::class)->desativar($ciclo);
    expect($ciclo->fresh()->status)->toBe('inativo');
});
