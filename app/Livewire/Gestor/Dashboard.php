<?php

declare(strict_types=1);

namespace App\Livewire\Gestor;

use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Contestacao;
use App\Models\Servidor;
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
            'ciclo'                   => $ciclo,
            'total_servidores'        => $servidoresIds->count(),
            'avaliacoes_pendentes'    => $ciclo
                ? Avaliacao::where('ciclo_id', $ciclo->id)
                    ->where('tipo', 'area')
                    ->where('status', 'rascunho')
                    ->whereIn('servidor_id', $servidoresIds)
                    ->count()
                : 0,
            'avaliacoes_enviadas'     => $ciclo
                ? Avaliacao::where('ciclo_id', $ciclo->id)
                    ->where('tipo', 'area')
                    ->where('status', 'enviada')
                    ->whereIn('servidor_id', $servidoresIds)
                    ->count()
                : 0,
            'contestacoes_pendentes'  => $ciclo
                ? Contestacao::where('status', 'pendente')
                    ->whereHas('avaliacao', fn ($q) => $q
                        ->where('ciclo_id', $ciclo->id)
                        ->whereIn('servidor_id', $servidoresIds))
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

        $contestacoes = $ciclo
            ? Contestacao::where('status', 'pendente')
                ->whereHas('avaliacao', fn ($q) => $q
                    ->where('ciclo_id', $ciclo->id)
                    ->whereIn('servidor_id', $servidoresIds))
                ->with(['servidor', 'avaliacao.competencia'])
                ->latest()
                ->take(5)
                ->get()
            : collect();

        return view('livewire.gestor.dashboard', compact('stats', 'servidores', 'contestacoes'))
            ->layout('layouts.app')
            ->title('Dashboard');
    }
}
