<?php

use App\Models\Area;
use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Models\ItemAvaliacao;
use App\Models\Servidor;
use Livewire\Livewire;

it('servidor vê tela de avaliações sem ciclo ativo', function () {
    $servidor = Servidor::factory()->create(['perfil' => 'servidor']);
    $this->actingAs($servidor->user);

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Index::class)
        ->assertSee('Nenhum ciclo ativo');
});

it('servidor vê competências do ciclo ativo', function () {
    $area = Area::factory()->create();
    $ciclo = Ciclo::factory()->ativo()->create();
    $servidor = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area->id]);
    $competencia = Competencia::factory()->create(['ativo' => true]);
    $competencia->areas()->attach($area);

    $this->actingAs($servidor->user);

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Index::class)
        ->assertSee($competencia->nome);
});

it('servidor pode iniciar avaliação', function () {
    $area = Area::factory()->create();
    $ciclo = Ciclo::factory()->ativo()->create();
    $servidor = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area->id]);
    $competencia = Competencia::factory()->create(['ativo' => true]);
    $competencia->areas()->attach($area);

    $this->actingAs($servidor->user);

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Index::class)
        ->call('iniciar', $competencia->id)
        ->assertRedirect();

    expect(Avaliacao::where('servidor_id', $servidor->id)->exists())->toBeTrue();
});

it('servidor preenche e salva rascunho', function () {
    $avaliacao = Avaliacao::factory()->create();
    $item = ItemAvaliacao::factory()->create(['competencia_id' => $avaliacao->competencia_id]);
    $servidor = $avaliacao->servidor;

    $this->actingAs($servidor->user);

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Form::class, ['avaliacao' => $avaliacao])
        ->set("notas.{$item->id}", '3')
        ->call('salvar')
        ->assertDispatched('toast');

    expect(\App\Models\RespostaAvaliacao::where('avaliacao_id', $avaliacao->id)->first()->nota)->toBe(3);
});

it('servidor envia avaliação com todos os itens preenchidos', function () {
    $avaliacao = Avaliacao::factory()->create();
    $itens = ItemAvaliacao::factory()->count(2)->create(['competencia_id' => $avaliacao->competencia_id]);
    $servidor = $avaliacao->servidor;

    $this->actingAs($servidor->user);

    $notas = [];
    foreach ($itens as $item) {
        $notas[$item->id] = '4';
    }

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Form::class, ['avaliacao' => $avaliacao])
        ->set('notas', $notas)
        ->call('enviar')
        ->assertRedirect();

    expect($avaliacao->refresh()->status)->toBe('enviada');
});

it('servidor não acessa formulário de avaliação alheia', function () {
    $outro = Servidor::factory()->create(['perfil' => 'servidor']);
    $avaliacao = Avaliacao::factory()->create();

    $this->actingAs($outro->user);

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Form::class, ['avaliacao' => $avaliacao])
        ->assertForbidden();
});

it('servidor vê resultado de avaliação enviada', function () {
    $avaliacao = Avaliacao::factory()->enviada()->create();
    $servidor = $avaliacao->servidor;

    $this->actingAs($servidor->user);

    Livewire::test(\App\Livewire\Servidor\Avaliacoes\Resultado::class, ['avaliacao' => $avaliacao])
        ->assertSee(number_format($avaliacao->media, 1));
});
