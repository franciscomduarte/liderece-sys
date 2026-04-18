<?php

use App\Livewire\Admin\Areas\Index;
use App\Models\Area;
use App\Models\Servidor;
use App\Services\AreaService;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = Servidor::factory()->admin()->create();
    $this->actingAs($this->admin->user);
});

it('exibe a listagem de áreas', function () {
    Area::factory()->count(3)->create();

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertSee(Area::first()->nome);
});

it('filtra áreas por busca', function () {
    Area::factory()->create(['nome' => 'Diretoria de TI']);
    Area::factory()->create(['nome' => 'Recursos Humanos']);

    Livewire::test(Index::class)
        ->set('search', 'Diretoria')
        ->assertSee('Diretoria de TI')
        ->assertDontSee('Recursos Humanos');
});

it('cria uma nova área', function () {
    Livewire::test(Index::class)
        ->call('openCreate')
        ->set('nome', 'Diretoria Financeira')
        ->set('responsavel', 'João Silva')
        ->set('descricao', 'Área de finanças')
        ->call('save')
        ->assertDispatched('toast');

    expect(Area::where('nome', 'Diretoria Financeira')->exists())->toBeTrue();
});

it('valida campos obrigatórios ao criar área', function () {
    Livewire::test(Index::class)
        ->call('openCreate')
        ->set('nome', '')
        ->call('save')
        ->assertHasErrors(['nome']);
});

it('edita uma área existente', function () {
    $area = Area::factory()->create(['nome' => 'Área Antiga']);

    Livewire::test(Index::class)
        ->call('openEdit', $area->id)
        ->set('nome', 'Área Atualizada')
        ->call('save');

    expect($area->fresh()->nome)->toBe('Área Atualizada');
});

it('exclui uma área sem servidores', function () {
    $area = Area::factory()->create();

    Livewire::test(Index::class)
        ->call('confirmDelete', $area->id)
        ->call('delete');

    expect(Area::find($area->id))->toBeNull();
});

it('não exclui área com servidores vinculados', function () {
    $area = Area::factory()->create();
    Servidor::factory()->create(['area_id' => $area->id]);

    Livewire::test(Index::class)
        ->call('confirmDelete', $area->id)
        ->call('delete')
        ->assertDispatched('toast');

    expect(Area::find($area->id))->not->toBeNull();
});

// AreaService unit tests
it('AreaService::store cria área corretamente', function () {
    $service = app(AreaService::class);
    $area = $service->store(['nome' => 'Nova Área', 'descricao' => null, 'responsavel' => null]);

    expect($area)->toBeInstanceOf(Area::class)
        ->and($area->nome)->toBe('Nova Área');
});

it('AreaService::delete lança exceção para área com servidores', function () {
    $area = Area::factory()->create();
    Servidor::factory()->create(['area_id' => $area->id]);

    expect(fn () => app(AreaService::class)->delete($area))
        ->toThrow(\RuntimeException::class);
});

// Testes de subáreas
it('cria subárea vinculada a uma área pai', function () {
    $pai = Area::factory()->create(['nome' => 'Diretoria de TI']);

    Livewire::test(Index::class)
        ->call('openCreate')
        ->set('nome', 'Setor de Desenvolvimento')
        ->set('parent_id', $pai->id)
        ->call('save')
        ->assertDispatched('toast');

    $sub = Area::where('nome', 'Setor de Desenvolvimento')->first();
    expect($sub->parent_id)->toBe($pai->id);
});

it('não exclui área que possui subáreas', function () {
    $pai  = Area::factory()->create();
    Area::factory()->subarea($pai)->create();

    Livewire::test(Index::class)
        ->call('confirmDelete', $pai->id)
        ->call('delete')
        ->assertDispatched('toast');

    expect(Area::find($pai->id))->not->toBeNull();
});

it('AreaService::delete lança exceção para área com subáreas', function () {
    $pai  = Area::factory()->create();
    Area::factory()->subarea($pai)->create();

    expect(fn () => app(AreaService::class)->delete($pai))
        ->toThrow(\RuntimeException::class, 'subáreas');
});

it('exibe subárea abaixo da área pai na listagem', function () {
    $pai = Area::factory()->create(['nome' => 'Diretoria Geral']);
    Area::factory()->subarea($pai)->create(['nome' => 'Setor Financeiro']);

    Livewire::test(Index::class)
        ->assertSee('Diretoria Geral')
        ->assertSee('Setor Financeiro');
});
