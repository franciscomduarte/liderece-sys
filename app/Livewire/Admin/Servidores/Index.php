<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Servidores;

use App\Models\Area;
use App\Models\Servidor;
use App\Services\ServidorService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filtroPerfil = '';
    public string $filtroArea = '';
    public string $filtroStatus = '';

    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showSenhaModal = false;
    public ?string $editingId = null;
    public ?string $deletingId = null;
    public ?string $novaSenha = null;

    public string $nome = '';
    public string $email = '';
    public string $matricula = '';
    public string $cargo = '';
    public string $area_id = '';
    public string $perfil = 'servidor';
    public string $status = 'ativo';

    protected function rules(): array
    {
        $emailRule = $this->editingId
            ? "required|email|unique:servidores,email,{$this->editingId}|unique:users,email,{$this->getUserId()}"
            : 'required|email|unique:servidores,email|unique:users,email';

        $matriculaRule = $this->editingId
            ? "required|string|max:20|unique:servidores,matricula,{$this->editingId}"
            : 'required|string|max:20|unique:servidores,matricula';

        return [
            'nome'      => 'required|string|max:150',
            'email'     => $emailRule,
            'matricula' => $matriculaRule,
            'cargo'     => 'required|string|max:100',
            'area_id'   => 'required|exists:areas,id',
            'perfil'    => 'required|in:admin,gestor,servidor',
            'status'    => 'required|in:ativo,inativo',
        ];
    }

    protected array $messages = [
        'nome.required'      => 'O nome é obrigatório.',
        'email.required'     => 'O e-mail é obrigatório.',
        'email.unique'       => 'Este e-mail já está cadastrado.',
        'matricula.required' => 'A matrícula é obrigatória.',
        'matricula.unique'   => 'Esta matrícula já está cadastrada.',
        'cargo.required'     => 'O cargo é obrigatório.',
        'area_id.required'   => 'A área é obrigatória.',
    ];

    private function getUserId(): int|null
    {
        if (! $this->editingId) {
            return null;
        }
        return Servidor::find($this->editingId)?->user_id;
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFiltroPerfil(): void { $this->resetPage(); }
    public function updatingFiltroArea(): void { $this->resetPage(); }
    public function updatingFiltroStatus(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['nome', 'email', 'matricula', 'cargo', 'area_id', 'editingId']);
        $this->perfil = 'servidor';
        $this->status = 'ativo';
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function openEdit(string $id): void
    {
        $s = Servidor::findOrFail($id);
        $this->editingId = $id;
        $this->nome      = $s->nome;
        $this->email     = $s->email;
        $this->matricula = $s->matricula;
        $this->cargo     = $s->cargo;
        $this->area_id   = $s->area_id ?? '';
        $this->perfil    = $s->perfil;
        $this->status    = $s->status;
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'nome'      => $this->nome,
            'email'     => $this->email,
            'matricula' => $this->matricula,
            'cargo'     => $this->cargo,
            'area_id'   => $this->area_id,
            'perfil'    => $this->perfil,
            'status'    => $this->status,
        ];

        $service = app(ServidorService::class);

        if ($this->editingId) {
            $service->update(Servidor::findOrFail($this->editingId), $data);
            $this->dispatch('toast', type: 'success', message: 'Servidor atualizado com sucesso!');
        } else {
            $service->store($data);
            $this->dispatch('toast', type: 'success', message: 'Servidor cadastrado! Ele precisará trocar a senha no primeiro acesso.');
        }

        $this->showModal = false;
        $this->reset(['nome', 'email', 'matricula', 'cargo', 'area_id', 'editingId']);
        $this->perfil = 'servidor';
        $this->status = 'ativo';
    }

    public function confirmDelete(string $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        try {
            app(ServidorService::class)->delete(Servidor::findOrFail($this->deletingId));
            $this->dispatch('toast', type: 'success', message: 'Servidor excluído com sucesso!');
        } catch (\Throwable $e) {
            $this->dispatch('toast', type: 'error', message: 'Erro ao excluir: ' . $e->getMessage());
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function resetSenha(string $id): void
    {
        $servidor = Servidor::findOrFail($id);
        $this->novaSenha = app(ServidorService::class)->resetSenha($servidor);
        $this->showSenhaModal = true;
    }

    public function render()
    {
        $servidores = Servidor::query()
            ->with('area')
            ->when($this->search, fn ($q) => $q->where(function ($q2) {
                $q2->where('nome', 'ilike', "%{$this->search}%")
                   ->orWhere('matricula', 'ilike', "%{$this->search}%")
                   ->orWhere('email', 'ilike', "%{$this->search}%");
            }))
            ->when($this->filtroPerfil, fn ($q) => $q->where('perfil', $this->filtroPerfil))
            ->when($this->filtroArea, fn ($q) => $q->where('area_id', $this->filtroArea))
            ->when($this->filtroStatus, fn ($q) => $q->where('status', $this->filtroStatus))
            ->orderBy('nome')
            ->paginate(15);

        $areas = Area::orderBy('nome')->get();

        return view('livewire.admin.servidores.index', compact('servidores', 'areas'))
            ->layout('layouts.app')
            ->title('Servidores');
    }
}
