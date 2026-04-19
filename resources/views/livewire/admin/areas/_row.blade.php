@php
$depth       ??= $indent ?? 0;
$hasChildren ??= false;
$ancestorIds ??= [];
$paddingLeft   = 16 + ($depth * 28);
@endphp
<tr
    x-show="isVisible({{ Js::from($ancestorIds) }})"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 -translate-y-1"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="hover:bg-[#f6fafe] transition-colors duration-200 group"
    style="display: none"
>
    <td style="padding-left: {{ $paddingLeft }}px; padding-right: 24px;" class="py-3.5">
        <div class="flex items-center gap-2">
            {{-- Botão expand/collapse ou espaço reservado --}}
            @if($hasChildren)
            <button
                @click.stop="toggle('{{ $area->id }}')"
                class="w-6 h-6 rounded-md hover:bg-[#eaeef2] flex items-center justify-center transition-all shrink-0"
                :title="isOpen('{{ $area->id }}') ? 'Recolher' : 'Expandir'"
            >
                <span
                    class="material-symbols-outlined text-[#727785] text-base transition-transform duration-200"
                    :class="isOpen('{{ $area->id }}') ? 'rotate-90' : ''"
                >chevron_right</span>
            </button>
            @else
            <span class="w-6 shrink-0 block"></span>
            @endif

            {{-- Ícone da área --}}
            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0
                {{ $depth === 0 ? 'bg-[#d8e2ff]' : ($depth === 1 ? 'bg-[#f0f4f8]' : 'bg-white border border-[#eaeef2]') }}">
                <span class="material-symbols-outlined text-base {{ $depth === 0 ? 'text-[#004395]' : 'text-[#727785]' }}">
                    {{ $depth === 0 ? 'domain' : 'account_tree' }}
                </span>
            </div>

            {{-- Nome + contagem de subáreas --}}
            <div>
                <p class="font-['Manrope'] font-{{ $depth === 0 ? 'bold' : ($depth === 1 ? 'semibold' : 'medium') }} text-[#171c1f] text-sm leading-tight">
                    {{ $area->nome }}
                </p>
                @if($hasChildren)
                <p class="text-xs text-[#0058be] mt-0.5">{{ $area->children_count }} subárea(s)</p>
                @elseif($area->descricao)
                <p class="text-xs text-[#727785] mt-0.5 line-clamp-1">{{ $area->descricao }}</p>
                @endif
            </div>
        </div>
    </td>

    <td class="px-6 py-3.5 hidden md:table-cell">
        @if($area->parent)
        <span class="inline-flex items-center gap-1 text-xs text-[#424754]">
            <span class="material-symbols-outlined text-sm">domain</span>
            {{ $area->parent->nome }}
        </span>
        @else
        <span class="text-xs text-[#c2c6d6]">—</span>
        @endif
    </td>

    <td class="px-6 py-3.5 hidden md:table-cell">
        <span class="text-sm text-[#424754]">{{ $area->responsavel ?? '—' }}</span>
    </td>

    <td class="px-6 py-3.5 text-center hidden sm:table-cell">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#dee2f7] text-[#414657]">
            {{ $area->servidores_count }}
        </span>
    </td>

    <td class="px-6 py-3.5">
        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
            <button wire:click="openEdit('{{ $area->id }}')"
                class="w-8 h-8 rounded-lg hover:bg-[#d8e2ff] flex items-center justify-center transition-colors" title="Editar">
                <span class="material-symbols-outlined text-[#0058be] text-base">edit</span>
            </button>
            <button wire:click="confirmDelete('{{ $area->id }}')"
                class="w-8 h-8 rounded-lg hover:bg-[#ffdad6] flex items-center justify-center transition-colors" title="Excluir">
                <span class="material-symbols-outlined text-[#ba1a1a] text-base">delete</span>
            </button>
        </div>
    </td>
</tr>
