<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Areas;

use App\Models\Area;
use App\Services\AreaService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?string $editingId = null;
    public ?string $deletingId = null;

    public string $nome = '';
    public string $descricao = '';
    public string $responsavel = '';
    public string $parent_id = '';

    protected function rules(): array
    {
        return [
            'nome'        => 'required|string|max:100',
            'descricao'   => 'nullable|string|max:500',
            'responsavel' => 'nullable|string|max:100',
            'parent_id'   => 'nullable|exists:areas,id',
        ];
    }

    protected array $messages = [
        'nome.required'   => 'O nome da área é obrigatório.',
        'nome.max'        => 'O nome não pode ultrapassar 100 caracteres.',
        'parent_id.exists'=> 'Área pai inválida.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset(['nome', 'descricao', 'responsavel', 'parent_id', 'editingId']);
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function openEdit(string $id): void
    {
        $area = Area::findOrFail($id);
        $this->editingId    = $id;
        $this->nome         = $area->nome;
        $this->descricao    = $area->descricao ?? '';
        $this->responsavel  = $area->responsavel ?? '';
        $this->parent_id    = $area->parent_id ?? '';
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'nome'        => $this->nome,
            'descricao'   => $this->descricao ?: null,
            'responsavel' => $this->responsavel ?: null,
            'parent_id'   => $this->parent_id ?: null,
        ];

        $service = app(AreaService::class);

        if ($this->editingId) {
            $service->update(Area::findOrFail($this->editingId), $data);
            $this->dispatch('toast', type: 'success', message: 'Área atualizada com sucesso!');
        } else {
            $service->store($data);
            $this->dispatch('toast', type: 'success', message: 'Área criada com sucesso!');
        }

        $this->showModal = false;
        $this->reset(['nome', 'descricao', 'responsavel', 'parent_id', 'editingId']);
    }

    public function confirmDelete(string $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        try {
            app(AreaService::class)->delete(Area::findOrFail($this->deletingId));
            $this->dispatch('toast', type: 'success', message: 'Área excluída com sucesso!');
        } catch (\RuntimeException $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        if ($this->search) {
            // busca plana quando filtro ativo
            $areas = Area::query()
                ->where('nome', 'ilike', "%{$this->search}%")
                ->withCount('servidores')
                ->with('parent')
                ->orderBy('nome')
                ->paginate(15);
            $arvore = null;
        } else {
            $areas  = null;
            // árvore: raízes com filhos aninhados
            $arvore = Area::whereNull('parent_id')
                ->with(['children' => fn ($q) => $q->withCount('servidores')->with('children.servidores')])
                ->withCount('servidores')
                ->orderBy('nome')
                ->get();
        }

        // Para o select "Área pai" no modal — carrega apenas quando modal está aberto
        $areasParaSelect = $this->showModal
            ? Area::with('parent')->orderBy('nome')
                ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
                ->get()
            : collect();

        return view('livewire.admin.areas.index', compact('areas', 'arvore', 'areasParaSelect'))
            ->layout('layouts.app')
            ->title('Áreas');
    }
}
