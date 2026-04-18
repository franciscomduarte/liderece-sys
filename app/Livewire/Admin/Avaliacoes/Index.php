<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Avaliacoes;

use App\Models\Area;
use App\Models\Avaliacao;
use App\Models\Ciclo;
use App\Services\AvaliacaoService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filtroCiclo     = '';
    public string $filtroArea      = '';
    public string $filtroTipo      = '';
    public string $filtroStatus    = '';
    public string $filtroBusca     = '';

    public ?string $reabrirId      = null;
    public bool $showConfirmModal  = false;

    protected $queryString = [
        'filtroCiclo'  => ['except' => ''],
        'filtroArea'   => ['except' => ''],
        'filtroTipo'   => ['except' => ''],
        'filtroStatus' => ['except' => ''],
        'filtroBusca'  => ['except' => ''],
    ];

    public function updatingFiltroCiclo(): void    { $this->resetPage(); }
    public function updatingFiltroArea(): void     { $this->resetPage(); }
    public function updatingFiltroTipo(): void     { $this->resetPage(); }
    public function updatingFiltroStatus(): void   { $this->resetPage(); }
    public function updatingFiltroBusca(): void    { $this->resetPage(); }

    public function confirmarReabrir(string $avaliacaoId): void
    {
        $this->reabrirId         = $avaliacaoId;
        $this->showConfirmModal  = true;
    }

    public function reabrir(): void
    {
        $avaliacao = Avaliacao::findOrFail($this->reabrirId);
        $this->authorize('reabrir', $avaliacao);

        try {
            app(AvaliacaoService::class)->reabrir($avaliacao);
            $this->dispatch('toast', type: 'success', message: 'Avaliação reaberta para edição.');
        } catch (\Throwable $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }

        $this->showConfirmModal = false;
        $this->reabrirId        = null;
    }

    public function cancelarReabrir(): void
    {
        $this->showConfirmModal = false;
        $this->reabrirId        = null;
    }

    public function render()
    {
        $ciclos = Ciclo::orderByDesc('data_inicio')->get();
        $areas  = Area::orderBy('nome')->get();

        $query = Avaliacao::with(['ciclo', 'servidor.area', 'avaliador', 'competencia'])
            ->orderByDesc('created_at');

        if ($this->filtroCiclo) {
            $query->where('ciclo_id', $this->filtroCiclo);
        }

        if ($this->filtroArea) {
            $query->whereHas('servidor', fn($q) => $q->where('area_id', $this->filtroArea));
        }

        if ($this->filtroTipo) {
            $query->where('tipo', $this->filtroTipo);
        }

        if ($this->filtroStatus) {
            $query->where('status', $this->filtroStatus);
        }

        if ($this->filtroBusca) {
            $busca = $this->filtroBusca;
            $query->whereHas('servidor', fn($q) => $q->where('nome', 'ilike', "%{$busca}%"));
        }

        $avaliacoes = $query->paginate(20);

        $stats = [
            'total'    => Avaliacao::count(),
            'enviadas' => Avaliacao::where('status', 'enviada')->count(),
            'rascunho' => Avaliacao::where('status', 'rascunho')->count(),
            'auto'     => Avaliacao::where('tipo', 'autoavaliacao')->where('status', 'enviada')->count(),
            'area'     => Avaliacao::where('tipo', 'area')->where('status', 'enviada')->count(),
        ];

        return view('livewire.admin.avaliacoes.index', compact('ciclos', 'areas', 'avaliacoes', 'stats'))
            ->layout('layouts.app')
            ->title('Avaliações');
    }
}
