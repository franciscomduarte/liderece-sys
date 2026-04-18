<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Competencias;

use App\Models\Area;
use App\Models\Competencia;
use App\Services\CompetenciaService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filtroTipo = '';

    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?string $editingId = null;
    public ?string $deletingId = null;

    public string $nome = '';
    public string $descricao = '';
    public string $tipo = 'comportamental';
    public bool $ativo = true;
    public array $itens = [['descricao' => '']];
    public array $areaIds = [];

    protected function rules(): array
    {
        return [
            'nome'             => 'required|string|max:150',
            'descricao'        => 'nullable|string|max:1000',
            'tipo'             => 'required|in:comportamental,técnica,gerencial',
            'ativo'            => 'boolean',
            'itens'            => 'required|array|min:1',
            'itens.*.descricao' => 'nullable|string|max:500',
            'areaIds'          => 'array',
            'areaIds.*'        => 'exists:areas,id',
        ];
    }

    protected array $messages = [
        'nome.required' => 'O nome da competência é obrigatório.',
        'tipo.required' => 'O tipo é obrigatório.',
        'itens.required' => 'Adicione ao menos um item de avaliação.',
    ];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFiltroTipo(): void { $this->resetPage(); }

    public function addItem(): void
    {
        $this->itens[] = ['descricao' => ''];
    }

    public function removeItem(int $index): void
    {
        if (count($this->itens) > 1) {
            array_splice($this->itens, $index, 1);
        }
    }

    public function openCreate(): void
    {
        $this->reset(['nome', 'descricao', 'editingId', 'areaIds']);
        $this->tipo  = 'comportamental';
        $this->ativo = true;
        $this->itens = [['descricao' => '']];
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function openEdit(string $id): void
    {
        $c = Competencia::with('itens', 'areas')->findOrFail($id);
        $this->editingId = $id;
        $this->nome      = $c->nome;
        $this->descricao = $c->descricao ?? '';
        $this->tipo      = $c->tipo;
        $this->ativo     = $c->ativo;
        $this->itens     = $c->itens->map(fn ($i) => ['descricao' => $i->descricao])->toArray();
        $this->areaIds   = $c->areas->pluck('id')->toArray();

        if (empty($this->itens)) {
            $this->itens = [['descricao' => '']];
        }

        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'nome'      => $this->nome,
            'descricao' => $this->descricao ?: null,
            'tipo'      => $this->tipo,
            'ativo'     => $this->ativo,
        ];

        $service = app(CompetenciaService::class);

        if ($this->editingId) {
            $service->update(Competencia::findOrFail($this->editingId), $data, $this->itens, $this->areaIds);
            $this->dispatch('toast', type: 'success', message: 'Competência atualizada com sucesso!');
        } else {
            $service->store($data, $this->itens, $this->areaIds);
            $this->dispatch('toast', type: 'success', message: 'Competência criada com sucesso!');
        }

        $this->showModal = false;
        $this->reset(['nome', 'descricao', 'editingId', 'areaIds']);
        $this->tipo  = 'comportamental';
        $this->ativo = true;
        $this->itens = [['descricao' => '']];
    }

    public function confirmDelete(string $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        try {
            app(CompetenciaService::class)->delete(Competencia::findOrFail($this->deletingId));
            $this->dispatch('toast', type: 'success', message: 'Competência excluída com sucesso!');
        } catch (\RuntimeException $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $competencias = Competencia::query()
            ->withCount('itens')
            ->when($this->search, fn ($q) => $q->where('nome', 'ilike', "%{$this->search}%"))
            ->when($this->filtroTipo, fn ($q) => $q->where('tipo', $this->filtroTipo))
            ->orderBy('nome')
            ->paginate(15);

        $areas = Area::orderBy('nome')->get();

        return view('livewire.admin.competencias.index', compact('competencias', 'areas'))
            ->layout('layouts.app')
            ->title('Competências');
    }
}
