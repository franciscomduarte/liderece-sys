<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Contestacoes;

use App\Models\Contestacao;
use App\Services\ContestacaoService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filtroStatus = '';
    public string $search = '';

    public ?string $respondendoId = null;
    public string $resposta = '';

    public function abrirResposta(string $id): void
    {
        $this->respondendoId = $id;
        $this->resposta = '';
        $this->resetErrorBag();
    }

    public function responder(): void
    {
        $this->validate(['resposta' => 'required|string|min:10|max:2000']);

        $contestacao = Contestacao::findOrFail($this->respondendoId);
        $this->authorize('responder', $contestacao);

        try {
            app(ContestacaoService::class)->responder($contestacao, $this->resposta);
            $this->respondendoId = null;
            $this->resposta = '';
            $this->dispatch('toast', type: 'success', message: 'Contestação respondida!');
        } catch (\Throwable $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFiltroStatus(): void { $this->resetPage(); }

    public function render()
    {
        $contestacoes = Contestacao::with(['avaliacao.competencia', 'avaliacao.ciclo', 'servidor.area'])
            ->when($this->filtroStatus, fn($q) => $q->where('status', $this->filtroStatus))
            ->when($this->search, fn($q) => $q->whereHas('servidor', fn($q2) =>
                $q2->where('nome', 'ilike', "%{$this->search}%")
            ))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totais = [
            'pendente'   => Contestacao::where('status', 'pendente')->count(),
            'respondida' => Contestacao::where('status', 'respondida')->count(),
            'encerrada'  => Contestacao::where('status', 'encerrada')->count(),
        ];

        return view('livewire.admin.contestacoes.index', compact('contestacoes', 'totais'))
            ->layout('layouts.app')
            ->title('Contestações');
    }
}
