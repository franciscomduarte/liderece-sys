<?php

declare(strict_types=1);

namespace App\Livewire\Servidor\Avaliacoes;

use App\Models\Ciclo;
use App\Models\Competencia;
use App\Services\AvaliacaoService;
use Livewire\Component;

class Index extends Component
{
    public function iniciar(string $competenciaId): void
    {
        $ciclo = Ciclo::cicloAtivo();
        if (! $ciclo) {
            $this->dispatch('toast', type: 'error', message: 'Não há ciclo ativo no momento.');
            return;
        }

        $servidor = auth()->user()->servidor;
        $competencia = Competencia::findOrFail($competenciaId);

        $avaliacao = app(AvaliacaoService::class)->obterOuCriar(
            $ciclo, $servidor, $servidor, $competencia, 'autoavaliacao'
        );

        $this->redirect(route('servidor.avaliacoes.form', $avaliacao));
    }

    public function render()
    {
        $ciclo = Ciclo::cicloAtivo();
        $servidor = auth()->user()->servidor;

        $competencias = collect();
        $avaliacoes = [];

        if ($ciclo && $servidor) {
            $service = app(AvaliacaoService::class);
            $competencias = $service->competenciasDoServidor($ciclo, $servidor);
            $avaliacoes = $service->avaliacoesIndexadas($ciclo, $servidor, 'autoavaliacao');
        }

        return view('livewire.servidor.avaliacoes.index', compact('ciclo', 'competencias', 'avaliacoes'))
            ->layout('layouts.app')
            ->title('Minhas Avaliações');
    }
}
