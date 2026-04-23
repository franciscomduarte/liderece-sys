@section('page-title', 'Servidores')
@section('page-subtitle', 'Gerencie os servidores do sistema')

<div>
<div class="space-y-6">

    {{-- Toolbar --}}
    <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center justify-between">
        <div class="flex flex-wrap gap-2 flex-1">
            <div class="relative flex-1 min-w-48">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#727785] text-lg pointer-events-none">search</span>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="Buscar por nome, matrícula ou e-mail..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all shadow-sm"
                >
            </div>
            <select wire:model.live="filtroPerfil" class="px-3 py-2.5 bg-white border border-[#c2c6d6] rounded-xl text-sm text-[#424754] focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all shadow-sm">
                <option value="">Todos os perfis</option>
                <option value="admin">Admin</option>
                <option value="gestor">Gestor</option>
                <option value="servidor">Servidor</option>
            </select>
            <select wire:model.live="filtroArea" class="px-3 py-2.5 bg-white border border-[#c2c6d6] rounded-xl text-sm text-[#424754] focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all shadow-sm">
                <option value="">Todas as áreas</option>
                @foreach($areas as $area)
                <option value="{{ $area->id }}">{{ $area->nome }}</option>
                @endforeach
            </select>
            <select wire:model.live="filtroStatus" class="px-3 py-2.5 bg-white border border-[#c2c6d6] rounded-xl text-sm text-[#424754] focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all shadow-sm">
                <option value="">Todos os status</option>
                <option value="ativo">Ativo</option>
                <option value="inativo">Inativo</option>
            </select>
        </div>
        <button
            wire:click="openCreate"
            class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all whitespace-nowrap"
        >
            <span class="material-symbols-outlined text-lg">person_add</span>
            Novo Servidor
        </button>
    </div>

    {{-- Tabela --}}
    <div class="bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] overflow-hidden ring-1 ring-black/[0.04]">
        <table class="w-full">
            <thead>
                <tr class="bg-[#f0f4f8] border-b border-[#eaeef2]">
                    <th class="text-left px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754]">Servidor</th>
                    <th class="text-left px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754] hidden md:table-cell">Matrícula / Cargo</th>
                    <th class="text-left px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754] hidden lg:table-cell">Área</th>
                    <th class="text-center px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754]">Perfil</th>
                    <th class="text-center px-6 py-3.5 text-xs font-bold uppercase tracking-widest text-[#424754] hidden sm:table-cell">Status</th>
                    <th class="px-6 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#eaeef2]">
                @forelse($servidores as $servidor)
                <tr
                    class="float-in hover:bg-[#f6fafe] transition-colors duration-200 group"
                    style="animation-delay: {{ $loop->index * 40 }}ms"
                >
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#0058be] to-[#2170e4] flex items-center justify-center shrink-0 text-white text-xs font-bold">
                                {{ strtoupper(substr($servidor->nome, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-['Manrope'] font-bold text-[#171c1f] text-sm">{{ $servidor->nome }}</p>
                                <p class="text-xs text-[#727785]">{{ $servidor->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        <p class="text-sm font-semibold text-[#171c1f]">{{ $servidor->matricula }}</p>
                        <p class="text-xs text-[#727785]">{{ $servidor->cargo }}</p>
                    </td>
                    <td class="px-6 py-4 hidden lg:table-cell">
                        <span class="text-sm text-[#424754]">{{ $servidor->area?->nome ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $badge = match($servidor->perfil) {
                                'admin'  => 'bg-[#d8e2ff] text-[#004395]',
                                'gestor' => 'bg-[#dee2f7] text-[#414657]',
                                default  => 'bg-[#f0f4f8] text-[#424754]',
                            };
                        @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badge }} capitalize">
                            {{ $servidor->perfil }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center hidden sm:table-cell">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $servidor->status === 'ativo' ? 'bg-[#6ffbbe]/30 text-[#005236]' : 'bg-[#f0f4f8] text-[#727785]' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $servidor->status === 'ativo' ? 'bg-[#006947]' : 'bg-[#727785]' }}"></span>
                            {{ ucfirst($servidor->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <button wire:click="resetSenha('{{ $servidor->id }}')" class="w-8 h-8 rounded-lg hover:bg-amber-100 flex items-center justify-center transition-colors" title="Resetar senha">
                                <span class="material-symbols-outlined text-amber-600 text-base">lock_reset</span>
                            </button>
                            <button wire:click="openEdit('{{ $servidor->id }}')" class="w-8 h-8 rounded-lg hover:bg-[#d8e2ff] flex items-center justify-center transition-colors" title="Editar">
                                <span class="material-symbols-outlined text-[#0058be] text-base">edit</span>
                            </button>
                            <button wire:click="confirmDelete('{{ $servidor->id }}')" class="w-8 h-8 rounded-lg hover:bg-[#ffdad6] flex items-center justify-center transition-colors" title="Excluir">
                                <span class="material-symbols-outlined text-[#ba1a1a] text-base">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">group_off</span>
                        <p class="text-[#727785] text-sm">Nenhum servidor encontrado.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($servidores->hasPages())
        <div class="px-6 py-4 border-t border-[#eaeef2]">{{ $servidores->links() }}</div>
        @endif
    </div>
</div>

{{-- Modal criar/editar --}}
<div x-data x-show="$wire.showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none">
    <div x-show="$wire.showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-xl ring-1 ring-black/[0.06] overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#eaeef2] sticky top-0 bg-white z-10 rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#d8e2ff] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#004395] text-lg">person</span>
                </div>
                <h2 class="font-['Manrope'] font-bold text-[#171c1f]">{{ $editingId ? 'Editar Servidor' : 'Novo Servidor' }}</h2>
            </div>
            <button wire:click="$set('showModal', false)" class="w-8 h-8 rounded-lg hover:bg-[#f0f4f8] flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[#727785] text-lg">close</span>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Nome completo <span class="text-[#ba1a1a]">*</span></label>
                    <input wire:model="nome" type="text" placeholder="Nome completo" class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('nome') border-[#ba1a1a] @else border-[#c2c6d6] @enderror">
                    @error('nome') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">E-mail <span class="text-[#ba1a1a]">*</span></label>
                    <input wire:model="email" type="email" placeholder="servidor@orgao.gov.br" class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('email') border-[#ba1a1a] @else border-[#c2c6d6] @enderror">
                    @error('email') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Matrícula <span class="text-[#ba1a1a]">*</span></label>
                    <input wire:model="matricula" type="text" placeholder="Ex: 1234567" class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('matricula') border-[#ba1a1a] @else border-[#c2c6d6] @enderror">
                    @error('matricula') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Cargo <span class="text-[#ba1a1a]">*</span></label>
                    <input wire:model="cargo" type="text" placeholder="Ex: Analista de TI" class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('cargo') border-[#ba1a1a] @else border-[#c2c6d6] @enderror">
                    @error('cargo') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Data de nascimento</label>
                    <input wire:model="data_nascimento" type="date" class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('data_nascimento') border-[#ba1a1a] @enderror">
                    @error('data_nascimento') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Data de ingresso no órgão</label>
                    <input wire:model="data_ingresso" type="date" class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('data_ingresso') border-[#ba1a1a] @enderror">
                    @error('data_ingresso') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Escolaridade</label>
                    <select wire:model="escolaridade" class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all bg-white">
                        <option value="">Não informado</option>
                        <option value="fundamental">Ensino Fundamental</option>
                        <option value="medio">Ensino Médio</option>
                        <option value="tecnico">Técnico</option>
                        <option value="superior">Ensino Superior</option>
                        <option value="especializacao">Especialização</option>
                        <option value="mestrado">Mestrado</option>
                        <option value="doutorado">Doutorado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Gênero</label>
                    <select wire:model="genero" class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all bg-white">
                        <option value="">Não informado</option>
                        <option value="masculino">Masculino</option>
                        <option value="feminino">Feminino</option>
                        <option value="nao_binario">Não binário</option>
                        <option value="outro">Outro</option>
                        <option value="prefiro_nao_informar">Prefiro não informar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Raça / Cor</label>
                    <select wire:model="raca" class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all bg-white">
                        <option value="">Não informado</option>
                        <option value="branca">Branca</option>
                        <option value="preta">Preta</option>
                        <option value="parda">Parda</option>
                        <option value="amarela">Amarela</option>
                        <option value="indigena">Indígena</option>
                        <option value="prefiro_nao_informar">Prefiro não informar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Área <span class="text-[#ba1a1a]">*</span></label>
                    <select wire:model="area_id" class="w-full px-3.5 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all @error('area_id') border-[#ba1a1a] @else border-[#c2c6d6] @enderror">
                        <option value="">Selecione a área</option>
                        @foreach($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->nome }}</option>
                        @endforeach
                    </select>
                    @error('area_id') <p class="text-xs text-[#ba1a1a] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Perfil</label>
                    <select wire:model="perfil" class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all">
                        <option value="servidor">Servidor</option>
                        <option value="gestor">Gestor</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#424754] uppercase tracking-wide mb-1.5">Status</label>
                    <select wire:model="status" class="w-full px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] transition-all">
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>
            </div>
            @if(!$editingId)
            <div class="flex items-start gap-3 bg-[#d8e2ff]/30 rounded-xl p-3">
                <span class="material-symbols-outlined text-[#004395] text-lg shrink-0 mt-0.5">info</span>
                <p class="text-xs text-[#004395]">Uma senha temporária será gerada automaticamente. O servidor precisará trocá-la no primeiro acesso.</p>
            </div>
            @endif
        </div>
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-[#eaeef2] bg-[#f6fafe] rounded-b-2xl">
            <button wire:click="$set('showModal', false)" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-[#424754] hover:bg-[#eaeef2] transition-colors">Cancelar</button>
            <button wire:click="save" wire:loading.attr="disabled" class="flex items-center gap-2 bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md shadow-[#0058be]/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                <span wire:loading wire:target="save" class="material-symbols-outlined text-base animate-spin">progress_activity</span>
                <span wire:loading.remove wire:target="save" class="material-symbols-outlined text-base">check</span>
                Salvar
            </button>
        </div>
    </div>
</div>

{{-- Modal nova senha --}}
<div x-data x-show="$wire.showSenhaModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none">
    <div x-show="$wire.showSenhaModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-sm ring-1 ring-black/[0.06] p-6 text-center">
        <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-amber-600 text-2xl">lock_reset</span>
        </div>
        <h3 class="font-['Manrope'] font-bold text-[#171c1f] text-lg mb-2">Senha resetada!</h3>
        <p class="text-sm text-[#727785] mb-4">Anote e informe a nova senha temporária ao servidor.</p>
        <div class="bg-[#f0f4f8] rounded-xl px-4 py-3 mb-4 font-mono font-bold text-[#171c1f] text-lg tracking-wider">{{ $novaSenha }}</div>
        <p class="text-xs text-[#727785] mb-5">O servidor precisará trocá-la no próximo acesso.</p>
        <button wire:click="$set('showSenhaModal', false)" class="w-full py-2.5 rounded-xl text-sm font-bold bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white">Entendido</button>
    </div>
</div>

{{-- Modal confirmar exclusão --}}
<div x-data x-show="$wire.showDeleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display:none">
    <div x-show="$wire.showDeleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="bg-white rounded-2xl shadow-[0_25px_80px_rgba(23,28,31,0.2)] w-full max-w-sm ring-1 ring-black/[0.06] p-6 text-center">
        <div class="w-14 h-14 rounded-2xl bg-[#ffdad6] flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-[#ba1a1a] text-2xl">person_remove</span>
        </div>
        <h3 class="font-['Manrope'] font-bold text-[#171c1f] text-lg mb-2">Excluir servidor?</h3>
        <p class="text-sm text-[#727785] mb-6">O usuário e todos os dados vinculados serão removidos permanentemente.</p>
        <div class="flex gap-3">
            <button wire:click="$set('showDeleteModal', false)" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-[#424754] border border-[#c2c6d6] hover:bg-[#f0f4f8] transition-colors">Cancelar</button>
            <button wire:click="delete" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-[#ba1a1a] hover:bg-[#93000a] transition-colors">Excluir</button>
        </div>
    </div>
</div>
</div>
