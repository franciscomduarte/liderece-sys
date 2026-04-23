<?php

declare(strict_types=1);

namespace App\Livewire\Servidor;

use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Services\GapService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $servidor = auth()->user()->servidor;
        $ciclo    = Ciclo::cicloAtivo();

        $enviadas = $ciclo
            ? Avaliacao::where('ciclo_id', $ciclo->id)
                ->where('servidor_id', $servidor->id)
                ->where('tipo', 'autoavaliacao')
                ->where('status', 'enviada')
                ->pluck('competencia_id')
            : collect();

        $autoavaliacoesPendentes = ($ciclo && $servidor->area_id)
            ? Competencia::ativas()
                ->whereHas('areas', fn($q) => $q->where('areas.id', $servidor->area_id))
                ->whereNotIn('id', $enviadas)
                ->orderBy('nome')
                ->get()
            : collect();

        $resultadosRecentes = Avaliacao::where('servidor_id', $servidor->id)
            ->where('tipo', 'area')
            ->where('status', 'enviada')
            ->with(['ciclo', 'competencia'])
            ->orderByDesc('enviada_at')
            ->take(5)
            ->get();

        $notificacoes = $servidor->notificacoes()
            ->where('lida', false)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'ciclo'                    => $ciclo,
            'autoavaliacoes_pendentes' => $autoavaliacoesPendentes->count(),
            'resultados_disponiveis'   => $ciclo
                ? Avaliacao::where('ciclo_id', $ciclo->id)
                    ->where('servidor_id', $servidor->id)
                    ->where('tipo', 'area')
                    ->where('status', 'enviada')
                    ->count()
                : 0,
            'notificacoes_nao_lidas'   => $servidor->notificacoes()->where('lida', false)->count(),
        ];

        $gapsServidor = $ciclo
            ? app(GapService::class)->gapsDoServidor($servidor, $ciclo)
            : collect();

        return view('livewire.servidor.dashboard', compact(
            'stats', 'autoavaliacoesPendentes', 'resultadosRecentes', 'notificacoes', 'gapsServidor'
        ))
            ->layout('layouts.app')
            ->title('Dashboard');
    }
}
