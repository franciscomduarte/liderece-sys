<?php

declare(strict_types=1);

namespace App\Livewire\Servidor\Avaliacoes;

use App\Models\Avaliacao;
use App\Services\ContestacaoService;
use Livewire\Component;

class Resultado extends Component
{
    public Avaliacao $avaliacao;
    public bool $showContestacaoModal = false;
    public string $justificativa = '';

    public function mount(Avaliacao $avaliacao): void
    {
        $this->authorize('view', $avaliacao);
        $this->avaliacao = $avaliacao->load([
            'competencia.itensAtivos',
            'respostas',
            'contestacao',
            'ciclo',
        ]);
    }

    public function contestar(): void
    {
        $this->validate(['justificativa' => 'required|string|min:20|max:2000']);

        $servidor = auth()->user()->servidor;
        $service = app(ContestacaoService::class);

        try {
            $service->contestar($this->avaliacao, $servidor, $this->justificativa);
            $this->avaliacao->refresh()->load('contestacao');
            $this->showContestacaoModal = false;
            $this->justificativa = '';
            $this->dispatch('toast', type: 'success', message: 'Contestação enviada com sucesso!');
        } catch (\Throwable $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
    }

    public function render()
    {
        $podeContestar = app(ContestacaoService::class)
            ->podeContestar($this->avaliacao, auth()->user()->servidor);

        return view('livewire.servidor.avaliacoes.resultado', compact('podeContestar'))
            ->layout('layouts.app')
            ->title('Resultado da Avaliação');
    }
}
