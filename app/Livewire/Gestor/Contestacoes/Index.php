<?php

declare(strict_types=1);

namespace App\Livewire\Gestor\Contestacoes;

use App\Models\Contestacao;
use App\Services\ContestacaoService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filtroStatus = 'pendente';

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

    public function updatingFiltroStatus(): void { $this->resetPage(); }

    public function render()
    {
        $gestor = auth()->user()->servidor;

        $contestacoes = Contestacao::with(['avaliacao.competencia', 'avaliacao.ciclo', 'servidor'])
            ->whereHas('avaliacao', fn($q) => $q->whereHas('servidor', fn($q2) =>
                $q2->where('area_id', $gestor->area_id)
            ))
            ->when($this->filtroStatus, fn($q) => $q->where('status', $this->filtroStatus))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.gestor.contestacoes.index', compact('contestacoes'))
            ->layout('layouts.app')
            ->title('Contestações');
    }
}
