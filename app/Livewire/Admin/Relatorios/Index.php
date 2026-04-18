<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Relatorios;

use App\Models\Ciclo;
use App\Services\RelatorioService;
use Livewire\Component;

class Index extends Component
{
    public string $cicloId = '';

    public function mount(): void
    {
        $ativo = Ciclo::cicloAtivo();
        $this->cicloId = $ativo?->id ?? Ciclo::latest()->first()?->id ?? '';
    }

    public function render(RelatorioService $service)
    {
        $ciclos = Ciclo::orderByDesc('created_at')->get();
        $ciclo  = $this->cicloId ? Ciclo::find($this->cicloId) : null;

        $resumo  = $ciclo ? $service->resumoGeral($ciclo) : null;
        $porArea = $ciclo ? $service->mediaPorArea($ciclo) : [];
        $status  = $ciclo ? $service->distribuicaoStatus($ciclo) : ['rascunho' => 0, 'enviada' => 0];
        $ranking = $ciclo ? $service->rankingServidores($ciclo) : [];

        return view('livewire.admin.relatorios.index', compact(
            'ciclos', 'ciclo', 'resumo', 'porArea', 'status', 'ranking'
        ))
            ->layout('layouts.app')
            ->title('Relatórios');
    }
}
