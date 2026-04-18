<?php

use App\Livewire\Admin\Servidores\Index;
use App\Models\Area;
use App\Models\Servidor;
use App\Models\User;
use App\Services\ServidorService;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = Servidor::factory()->admin()->create();
    $this->area = Area::factory()->create();
    $this->actingAs($this->admin->user);
});

it('exibe a listagem de servidores', function () {
    $servidor = Servidor::factory()->create(['area_id' => $this->area->id]);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertSee($servidor->nome);
});

it('filtra servidores por nome', function () {
    Servidor::factory()->create(['nome' => 'Carlos Silva', 'area_id' => $this->area->id]);
    Servidor::factory()->create(['nome' => 'Ana Lima', 'area_id' => $this->area->id]);

    Livewire::test(Index::class)
        ->set('search', 'Carlos')
        ->assertSee('Carlos Silva')
        ->assertDontSee('Ana Lima');
});

it('filtra servidores por perfil', function () {
    Servidor::factory()->gestor()->create(['area_id' => $this->area->id]);
    Servidor::factory()->servidor()->create(['area_id' => $this->area->id]);

    $test = Livewire::test(Index::class)->set('filtroPerfil', 'gestor');

    expect($test->viewData('servidores')->where('perfil', 'servidor')->count())->toBe(0);
});

it('cria um novo servidor', function () {
    Livewire::test(Index::class)
        ->call('openCreate')
        ->set('nome', 'Maria Santos')
        ->set('email', 'maria@orgao.gov.br')
        ->set('matricula', '9999999')
        ->set('cargo', 'Analista')
        ->set('area_id', $this->area->id)
        ->set('perfil', 'servidor')
        ->set('status', 'ativo')
        ->call('save')
        ->assertDispatched('toast');

    expect(Servidor::where('email', 'maria@orgao.gov.br')->exists())->toBeTrue();
    expect(User::where('email', 'maria@orgao.gov.br')->exists())->toBeTrue();
});

it('valida e-mail duplicado ao criar servidor', function () {
    Servidor::factory()->create(['email' => 'existente@orgao.gov.br', 'area_id' => $this->area->id]);

    Livewire::test(Index::class)
        ->call('openCreate')
        ->set('nome', 'Outro')
        ->set('email', 'existente@orgao.gov.br')
        ->set('matricula', '0000001')
        ->set('cargo', 'Cargo')
        ->set('area_id', $this->area->id)
        ->call('save')
        ->assertHasErrors(['email']);
});

it('reseta senha do servidor', function () {
    $servidor = Servidor::factory()->create(['area_id' => $this->area->id]);
    $hashAnterior = $servidor->user->password;

    Livewire::test(Index::class)
        ->call('resetSenha', $servidor->id)
        ->assertSet('showSenhaModal', true);

    expect($servidor->fresh()->primeiro_acesso)->toBeTrue();
    expect($servidor->user->fresh()->password)->not->toBe($hashAnterior);
});

// ServidorService unit tests
it('ServidorService::store cria User e Servidor', function () {
    $service = app(ServidorService::class);
    $servidor = $service->store([
        'nome'      => 'Test User',
        'email'     => 'testuser@test.com',
        'matricula' => '1111111',
        'cargo'     => 'Cargo',
        'area_id'   => $this->area->id,
        'perfil'    => 'servidor',
        'status'    => 'ativo',
    ]);

    expect($servidor)->toBeInstanceOf(Servidor::class)
        ->and($servidor->primeiro_acesso)->toBeTrue()
        ->and(User::where('email', 'testuser@test.com')->exists())->toBeTrue();
});

it('ServidorService::resetSenha força primeiro_acesso', function () {
    $servidor = Servidor::factory()->create(['area_id' => $this->area->id, 'primeiro_acesso' => false]);

    $novaSenha = app(ServidorService::class)->resetSenha($servidor);

    expect($novaSenha)->toBeString()
        ->and($servidor->fresh()->primeiro_acesso)->toBeTrue();
});
