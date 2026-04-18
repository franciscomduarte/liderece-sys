<?php

declare(strict_types=1);

namespace App\Livewire\Gestor\Avaliacoes;

use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Models\Servidor;
use App\Services\AvaliacaoService;
use Livewire\Component;

class Index extends Component
{
    public string $filtroServidor = '';

    public function iniciarAvaliacao(string $servidorId, string $competenciaId): void
    {
        $ciclo = Ciclo::cicloAtivo();
        if (! $ciclo) {
            $this->dispatch('toast', type: 'error', message: 'Não há ciclo ativo no momento.');
            return;
        }

        $gestor = auth()->user()->servidor;
        $avaliado = Servidor::findOrFail($servidorId);

        if ($avaliado->area_id !== $gestor->area_id) {
            abort(403);
        }

        $avaliacao = app(AvaliacaoService::class)->obterOuCriar(
            $ciclo, $avaliado, $gestor, Competencia::findOrFail($competenciaId), 'area'
        );

        $this->redirect(route('gestor.avaliacoes.form', $avaliacao));
    }

    public function render()
    {
        $ciclo = Ciclo::cicloAtivo();
        $gestor = auth()->user()->servidor;

        $servidores = collect();
        $competencias = collect();
        $autoavaliacoes = [];
        $gestorAvaliacoes = [];

        if ($ciclo && $gestor) {
            $service = app(AvaliacaoService::class);

            $query = Servidor::ativos()
                ->where('area_id', $gestor->area_id)
                ->where('perfil', 'servidor')
                ->orderBy('nome');

            if ($this->filtroServidor) {
                $query->where('id', $this->filtroServidor);
            }

            $servidores = $query->get();

            $competencias = Competencia::ativas()
                ->whereHas('areas', fn($q) => $q->where('areas.id', $gestor->area_id))
                ->orderBy('nome')
                ->get();

            $avaliacoesRaw = Avaliacao::where('ciclo_id', $ciclo->id)
                ->whereIn('servidor_id', $servidores->pluck('id'))
                ->get();

            foreach ($avaliacoesRaw as $av) {
                if ($av->tipo === 'autoavaliacao') {
                    $autoavaliacoes[$av->servidor_id][$av->competencia_id] = $av;
                } else {
                    $gestorAvaliacoes[$av->servidor_id][$av->competencia_id] = $av;
                }
            }
        }

        return view('livewire.gestor.avaliacoes.index', compact(
            'ciclo', 'servidores', 'competencias', 'autoavaliacoes', 'gestorAvaliacoes'
        ))
            ->layout('layouts.app')
            ->title('Avaliações da Área');
    }
}
