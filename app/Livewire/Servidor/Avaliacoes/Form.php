<?php

declare(strict_types=1);

namespace App\Livewire\Servidor\Avaliacoes;

use App\Models\Avaliacao;
use App\Services\AvaliacaoService;
use Livewire\Component;

class Form extends Component
{
    public Avaliacao $avaliacao;
    public array $notas = [];
    public bool $showConfirmModal = false;

    public function mount(Avaliacao $avaliacao): void
    {
        $this->authorize('fill', $avaliacao);
        $this->avaliacao = $avaliacao->load(['competencia.itensAtivos', 'respostas']);

        foreach ($this->avaliacao->respostas as $resposta) {
            $this->notas[$resposta->item_id] = (string) $resposta->nota;
        }
    }

    public function salvar(): void
    {
        $this->authorize('fill', $this->avaliacao);

        try {
            app(AvaliacaoService::class)->salvarRespostas($this->avaliacao, $this->notas);
            $this->dispatch('toast', type: 'success', message: 'Rascunho salvo com sucesso!');
        } catch (\Throwable $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
    }

    public function confirmarEnvio(): void
    {
        $this->showConfirmModal = true;
    }

    public function enviar(): void
    {
        $this->authorize('fill', $this->avaliacao);
        $this->showConfirmModal = false;

        try {
            app(AvaliacaoService::class)->enviar($this->avaliacao, $this->notas);
            $this->dispatch('toast', type: 'success', message: 'Avaliação enviada com sucesso!');
            $this->redirect(route('servidor.avaliacoes.resultado', $this->avaliacao));
        } catch (\Throwable $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.servidor.avaliacoes.form')
            ->layout('layouts.app')
            ->title('Preencher Avaliação');
    }
}
