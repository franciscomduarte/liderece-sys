<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Configuracoes;

use App\Models\Ciclo;
use App\Services\CicloService;
use Livewire\Component;

class Ciclos extends Component
{
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?string $deletingId = null;

    public string $nome = '';
    public string $data_inicio = '';
    public string $data_fim = '';
    public int $prazo_contestacao_dias = 10;

    protected function rules(): array
    {
        return [
            'nome'                   => 'required|string|max:100',
            'data_inicio'            => 'required|date',
            'data_fim'               => 'required|date|after:data_inicio',
            'prazo_contestacao_dias' => 'required|integer|min:1|max:90',
        ];
    }

    protected array $messages = [
        'nome.required'                   => 'O nome do ciclo é obrigatório.',
        'data_inicio.required'            => 'A data de início é obrigatória.',
        'data_fim.required'               => 'A data de término é obrigatória.',
        'data_fim.after'                  => 'A data de término deve ser posterior ao início.',
        'prazo_contestacao_dias.required' => 'O prazo de contestação é obrigatório.',
        'prazo_contestacao_dias.min'      => 'O prazo mínimo é 1 dia.',
    ];

    public function openCreate(): void
    {
        $this->reset(['nome', 'data_inicio', 'data_fim']);
        $this->prazo_contestacao_dias = 10;
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $servidor = auth()->user()->servidor;

        try {
            app(CicloService::class)->store([
                'nome'                   => $this->nome,
                'data_inicio'            => $this->data_inicio,
                'data_fim'               => $this->data_fim,
                'prazo_contestacao_dias' => $this->prazo_contestacao_dias,
            ], $servidor);

            $this->dispatch('toast', type: 'success', message: 'Ciclo criado com sucesso!');
            $this->showModal = false;
            $this->reset(['nome', 'data_inicio', 'data_fim']);
            $this->prazo_contestacao_dias = 10;
        } catch (\RuntimeException $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
    }

    public function ativar(string $id): void
    {
        try {
            app(CicloService::class)->ativar(Ciclo::findOrFail($id));
            $this->dispatch('toast', type: 'success', message: 'Ciclo ativado com sucesso!');
        } catch (\RuntimeException $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
    }

    public function desativar(string $id): void
    {
        app(CicloService::class)->desativar(Ciclo::findOrFail($id));
        $this->dispatch('toast', type: 'success', message: 'Ciclo desativado.');
    }

    public function confirmDelete(string $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        try {
            app(CicloService::class)->delete(Ciclo::findOrFail($this->deletingId));
            $this->dispatch('toast', type: 'success', message: 'Ciclo excluído.');
        } catch (\RuntimeException $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $ciclos = Ciclo::with('criador')->latest()->get();

        return view('livewire.admin.configuracoes.ciclos', compact('ciclos'))
            ->layout('layouts.app')
            ->title('Ciclos de Avaliação');
    }
}
