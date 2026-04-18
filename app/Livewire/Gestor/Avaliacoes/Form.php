<?php

declare(strict_types=1);

namespace App\Livewire\Gestor\Avaliacoes;

use App\Models\Avaliacao;
use App\Services\AvaliacaoService;
use Livewire\Component;

class Form extends Component
{
    public Avaliacao $avaliacao;
    public ?Avaliacao $autoavaliacao = null;
    public array $notas = [];
    public string $comentario = '';
    public bool $showConfirmModal = false;

    public function mount(Avaliacao $avaliacao): void
    {
        $this->authorize('fill', $avaliacao);
        $this->avaliacao = $avaliacao->load(['competencia.itensAtivos', 'respostas', 'servidor']);
        $this->comentario = $avaliacao->comentario_gestor ?? '';

        $this->autoavaliacao = Avaliacao::where([
            'ciclo_id'       => $avaliacao->ciclo_id,
            'servidor_id'    => $avaliacao->servidor_id,
            'competencia_id' => $avaliacao->competencia_id,
            'tipo'           => 'autoavaliacao',
        ])->with('respostas')->first();

        foreach ($this->avaliacao->respostas as $resposta) {
            $this->notas[$resposta->item_id] = (string) $resposta->nota;
        }
    }

    public function salvar(): void
    {
        $this->authorize('fill', $this->avaliacao);
        $service = app(AvaliacaoService::class);

        try {
            $service->salvarRespostas($this->avaliacao, $this->notas);
            if ($this->comentario !== '') {
                $service->salvarComentarioGestor($this->avaliacao, $this->comentario);
            }
            $this->dispatch('toast', type: 'success', message: 'Rascunho salvo!');
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

        $service = app(AvaliacaoService::class);

        try {
            if ($this->comentario !== '') {
                $service->salvarComentarioGestor($this->avaliacao, $this->comentario);
            }
            $service->enviar($this->avaliacao, $this->notas);
            $this->dispatch('toast', type: 'success', message: 'Avaliação enviada!');
            $this->redirect(route('gestor.avaliacoes'));
        } catch (\Throwable $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.gestor.avaliacoes.form')
            ->layout('layouts.app')
            ->title('Avaliar Servidor');
    }
}
