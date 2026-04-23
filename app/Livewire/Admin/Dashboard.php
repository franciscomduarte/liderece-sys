<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Area;
use App\Models\Ciclo;
use App\Models\Competencia;
use App\Models\Servidor;
use App\Services\GapService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $ciclo = Ciclo::cicloAtivo();

        $stats = [
            'servidores'  => Servidor::where('status', 'ativo')->count(),
            'areas'       => Area::count(),
            'competencias'=> Competencia::where('ativo', true)->count(),
            'ciclo_ativo' => $ciclo?->nome ?? null,
        ];

        $resumoGap = $ciclo
            ? app(GapService::class)->resumoGeral($ciclo)
            : null;

        return view('livewire.admin.dashboard', compact('stats', 'resumoGap'))
            ->layout('layouts.app')
            ->title('Dashboard');
    }
}
