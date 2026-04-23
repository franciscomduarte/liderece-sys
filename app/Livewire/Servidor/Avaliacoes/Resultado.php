<?php

declare(strict_types=1);

namespace App\Livewire\Servidor\Avaliacoes;

use App\Models\Avaliacao;
use Livewire\Component;

class Resultado extends Component
{
    public Avaliacao $avaliacao;

    public function mount(Avaliacao $avaliacao): void
    {
        $this->authorize('view', $avaliacao);
        $this->avaliacao = $avaliacao->load([
            'competencia.itensAtivos',
            'respostas',
            'ciclo',
        ]);
    }

    public function render()
    {
        return view('livewire.servidor.avaliacoes.resultado')
            ->layout('layouts.app')
            ->title('Resultado da Avaliação');
    }
}
