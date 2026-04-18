<?php

use App\Livewire\Admin\Competencias\Index;
use App\Models\Area;
use App\Models\Competencia;
use App\Models\Servidor;
use App\Services\CompetenciaService;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = Servidor::factory()->admin()->create();
    $this->area = Area::factory()->create();
    $this->actingAs($this->admin->user);
});

it('exibe listagem de competências', function () {
    $competencia = Competencia::factory()->create();

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertSee($competencia->nome);
});

it('filtra por tipo', function () {
    Competencia::factory()->create(['tipo' => 'comportamental', 'nome' => 'Comp Comp']);
    Competencia::factory()->create(['tipo' => 'técnica', 'nome' => 'Comp Técnica']);

    $test = Livewire::test(Index::class)->set('filtroTipo', 'técnica');
    expect($test->viewData('competencias')->where('tipo', 'comportamental')->count())->toBe(0);
});

it('cria competência com itens', function () {
    Livewire::test(Index::class)
        ->call('openCreate')
        ->set('nome', 'Liderança')
        ->set('tipo', 'gerencial')
        ->set('itens', [
            ['descricao' => 'Motiva a equipe'],
            ['descricao' => 'Delega bem'],
        ])
        ->call('save')
        ->assertDispatched('toast');

    $comp = Competencia::where('nome', 'Liderança')->first();
    expect($comp)->not->toBeNull()
        ->and($comp->itens()->count())->toBe(2);
});

it('pode adicionar e remover itens dinamicamente', function () {
    Livewire::test(Index::class)
        ->call('openCreate')
        ->call('addItem')
        ->assertCount('itens', 2)
        ->call('removeItem', 0)
        ->assertCount('itens', 1);
});

it('não exclui competência com avaliações', function () {
    $comp = Competencia::factory()->create();

    expect(fn () => app(CompetenciaService::class)->delete($comp))
        ->not->toThrow(\RuntimeException::class);

    expect(Competencia::find($comp->id))->toBeNull();
});

// CompetenciaService unit tests
it('CompetenciaService::store cria competência com itens e áreas', function () {
    $comp = app(CompetenciaService::class)->store(
        ['nome' => 'Comunicação', 'tipo' => 'comportamental', 'ativo' => true],
        [['descricao' => 'Comunica com clareza']],
        [$this->area->id]
    );

    expect($comp->itens()->count())->toBe(1)
        ->and($comp->areas()->count())->toBe(1);
});

it('CompetenciaService::update sincroniza itens', function () {
    $comp = app(CompetenciaService::class)->store(
        ['nome' => 'Comp', 'tipo' => 'técnica', 'ativo' => true],
        [['descricao' => 'Item antigo']]
    );

    app(CompetenciaService::class)->update(
        $comp,
        ['nome' => 'Comp', 'tipo' => 'técnica', 'ativo' => true],
        [['descricao' => 'Item novo 1'], ['descricao' => 'Item novo 2']]
    );

    expect($comp->fresh()->itens()->count())->toBe(2);
});
