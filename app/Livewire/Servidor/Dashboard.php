<?php

declare(strict_types=1);

namespace App\Livewire\Servidor;

use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Models\Contestacao;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $servidor = auth()->user()->servidor;
        $ciclo    = Ciclo::cicloAtivo();

        // IDs de competências já concluídas (enviadas) no ciclo ativo
        $enviadas = $ciclo
            ? Avaliacao::where('ciclo_id', $ciclo->id)
                ->where('servidor_id', $servidor->id)
                ->where('tipo', 'autoavaliacao')
                ->where('status', 'enviada')
                ->pluck('competencia_id')
            : collect();

        // Competências pendentes = ativas para a área do servidor menos as já enviadas
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
            ->with(['ciclo', 'competencia', 'contestacao'])
            ->orderByDesc('enviada_at')
            ->take(5)
            ->get();

        $notificacoes = $servidor->notificacoes()
            ->where('lida', false)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'ciclo'                      => $ciclo,
            'autoavaliacoes_pendentes'   => $autoavaliacoesPendentes->count(),
            'resultados_disponiveis'     => $ciclo
                ? Avaliacao::where('ciclo_id', $ciclo->id)
                    ->where('servidor_id', $servidor->id)
                    ->where('tipo', 'area')
                    ->where('status', 'enviada')
                    ->count()
                : 0,
            'notificacoes_nao_lidas'     => $servidor->notificacoes()->where('lida', false)->count(),
            'contestacoes_pendentes'     => Contestacao::where('servidor_id', $servidor->id)
                ->where('status', 'pendente')
                ->count(),
        ];

        return view('livewire.servidor.dashboard', compact(
            'stats', 'autoavaliacoesPendentes', 'resultadosRecentes', 'notificacoes'
        ))
            ->layout('layouts.app')
            ->title('Dashboard');
    }
}
