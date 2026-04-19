@php
// compatibilidade com chamadas que ainda passem $indent
$depth ??= $indent ?? 0;
$paddingLeft = 24 + ($depth * 28); // px — 24px base + 28px por nível
@endphp
<tr class="float-in hover:bg-[#f6fafe] transition-colors duration-200 group">
    <td style="padding-left: {{ $paddingLeft }}px; padding-right: 24px;" class="py-4">
        <div class="flex items-center gap-3">
            @if($depth > 0)
            <span class="text-[#c2c6d6] material-symbols-outlined text-base shrink-0">subdirectory_arrow_right</span>
            @endif
            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0
                {{ $depth === 0 ? 'bg-[#d8e2ff]' : ($depth === 1 ? 'bg-[#f0f4f8]' : 'bg-white border border-[#eaeef2]') }}">
                <span class="material-symbols-outlined text-base {{ $depth === 0 ? 'text-[#004395]' : 'text-[#727785]' }}">
                    {{ $depth === 0 ? 'domain' : 'account_tree' }}
                </span>
            </div>
            <div>
                <p class="font-['Manrope'] font-{{ $depth === 0 ? 'bold' : ($depth === 1 ? 'semibold' : 'medium') }} text-[#171c1f] text-sm">
                    {{ $area->nome }}
                </p>
                @if($area->descricao)
                <p class="text-xs text-[#727785] mt-0.5 line-clamp-1">{{ $area->descricao }}</p>
                @endif
                @if($area->servidores_count > 0)
                <p class="text-xs text-[#727785] mt-0.5">{{ $area->servidores_count }} servidor(es)</p>
                @endif
            </div>
        </div>
    </td>
    <td class="px-6 py-4 hidden md:table-cell">
        @if($area->parent)
        <span class="inline-flex items-center gap-1 text-xs text-[#424754]">
            <span class="material-symbols-outlined text-sm">domain</span>
            {{ $area->parent->nome }}
        </span>
        @else
        <span class="text-xs text-[#c2c6d6]">—</span>
        @endif
    </td>
    <td class="px-6 py-4 hidden md:table-cell">
        <span class="text-sm text-[#424754]">{{ $area->responsavel ?? '—' }}</span>
    </td>
    <td class="px-6 py-4 text-center hidden sm:table-cell">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#dee2f7] text-[#414657]">
            {{ $area->servidores_count }}
        </span>
    </td>
    <td class="px-6 py-4">
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
