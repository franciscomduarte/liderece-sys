<?php

use App\Models\Area;
use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Models\ItemAvaliacao;
use App\Models\Servidor;
use Livewire\Livewire;

it('gestor vê servidores da sua área', function () {
    $area = Area::factory()->create();
    $ciclo = Ciclo::factory()->ativo()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area->id]);
    $servidor = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area->id]);
    $competencia = Competencia::factory()->create(['ativo' => true]);
    $competencia->areas()->attach($area);

    $this->actingAs($gestor->user);

    Livewire::test(\App\Livewire\Gestor\Avaliacoes\Index::class)
        ->assertSee($servidor->nome);
});

it('gestor não vê servidores de outra área', function () {
    $area1 = Area::factory()->create();
    $area2 = Area::factory()->create();
    Ciclo::factory()->ativo()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area1->id]);
    $outro = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area2->id]);

    $this->actingAs($gestor->user);

    Livewire::test(\App\Livewire\Gestor\Avaliacoes\Index::class)
        ->assertDontSee($outro->nome);
});

it('gestor pode iniciar avaliação de servidor da sua área', function () {
    $area = Area::factory()->create();
    $ciclo = Ciclo::factory()->ativo()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area->id]);
    $servidor = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area->id]);
    $competencia = Competencia::factory()->create(['ativo' => true]);
    $competencia->areas()->attach($area);

    $this->actingAs($gestor->user);

    Livewire::test(\App\Livewire\Gestor\Avaliacoes\Index::class)
        ->call('iniciarAvaliacao', $servidor->id, $competencia->id)
        ->assertRedirect();

    expect(Avaliacao::where('avaliador_id', $gestor->id)->where('tipo', 'area')->exists())->toBeTrue();
});

it('gestor não pode avaliar servidor de outra área', function () {
    $area1 = Area::factory()->create();
    $area2 = Area::factory()->create();
    Ciclo::factory()->ativo()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area1->id]);
    $outro = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area2->id]);
    $competencia = Competencia::factory()->create(['ativo' => true]);

    $this->actingAs($gestor->user);

    Livewire::test(\App\Livewire\Gestor\Avaliacoes\Index::class)
        ->call('iniciarAvaliacao', $outro->id, $competencia->id)
        ->assertForbidden();
});

it('gestor envia avaliação com todos os itens preenchidos', function () {
    $area = Area::factory()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area->id]);
    $servidor = Servidor::factory()->create(['perfil' => 'servidor', 'area_id' => $area->id]);
    $avaliacao = Avaliacao::factory()->area()->create([
        'avaliador_id' => $gestor->id,
        'servidor_id'  => $servidor->id,
    ]);
    $itens = ItemAvaliacao::factory()->count(2)->create(['competencia_id' => $avaliacao->competencia_id]);

    $this->actingAs($gestor->user);

    $notas = [];
    foreach ($itens as $item) {
        $notas[$item->id] = '5';
    }

    Livewire::test(\App\Livewire\Gestor\Avaliacoes\Form::class, ['avaliacao' => $avaliacao])
        ->set('notas', $notas)
        ->call('enviar')
        ->assertRedirect();

    expect($avaliacao->refresh()->status)->toBe('enviada');
});

it('gestor não acessa formulário de avaliação de outra área', function () {
    $area1 = Area::factory()->create();
    $area2 = Area::factory()->create();
    $gestor = Servidor::factory()->create(['perfil' => 'gestor', 'area_id' => $area1->id]);
    $avaliacao = Avaliacao::factory()->area()->create([
        'avaliador_id' => Servidor::factory()->create(['area_id' => $area2->id])->id,
    ]);

    $this->actingAs($gestor->user);

    Livewire::test(\App\Livewire\Gestor\Avaliacoes\Form::class, ['avaliacao' => $avaliacao])
        ->assertForbidden();
});
