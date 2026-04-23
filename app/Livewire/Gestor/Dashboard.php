<?php

declare(strict_types=1);

namespace App\Livewire\Gestor;

use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Servidor;
use App\Services\GapService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $gestor = auth()->user()->servidor;
        $ciclo  = Ciclo::cicloAtivo();
        $areaId = $gestor->area_id;

        $servidoresIds = $areaId
            ? Servidor::where('area_id', $areaId)->where('perfil', 'servidor')->pluck('id')
            : collect();

        $stats = [
            'ciclo'                => $ciclo,
            'total_servidores'     => $servidoresIds->count(),
            'avaliacoes_pendentes' => $ciclo
                ? Avaliacao::where('ciclo_id', $ciclo->id)
                    ->where('tipo', 'area')
                    ->where('status', 'rascunho')
                    ->whereIn('servidor_id', $servidoresIds)
                    ->count()
                : 0,
            'avaliacoes_enviadas'  => $ciclo
                ? Avaliacao::where('ciclo_id', $ciclo->id)
                    ->where('tipo', 'area')
                    ->where('status', 'enviada')
                    ->whereIn('servidor_id', $servidoresIds)
                    ->count()
                : 0,
        ];

        $servidores = $areaId
            ? Servidor::where('area_id', $areaId)
                ->where('perfil', 'servidor')
                ->where('status', 'ativo')
                ->orderBy('nome')
                ->with(['avaliacoes' => fn ($q) => $ciclo
                    ? $q->where('ciclo_id', $ciclo->id)->where('tipo', 'area')
                    : $q->whereRaw('1=0')])
                ->take(8)
                ->get()
            : collect();

        $gapService    = app(GapService::class);
        $gapsGestor    = $ciclo ? $gapService->gapsDoServidor($gestor, $ciclo) : collect();
        $gapsDaEquipe  = ($ciclo && $areaId) ? $gapService->gapsDaArea($areaId, $ciclo) : collect();

        return view('livewire.gestor.dashboard', compact('stats', 'servidores', 'gapsGestor', 'gapsDaEquipe'))
            ->layout('layouts.app')
            ->title('Dashboard');
    }
}
