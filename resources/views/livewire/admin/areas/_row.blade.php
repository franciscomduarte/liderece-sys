@php
$paddings = [0 => 'px-6', 1 => 'pl-12 pr-6', 2 => 'pl-20 pr-6'];
$pad = $paddings[$indent] ?? 'pl-20 pr-6';
@endphp
<tr class="float-in hover:bg-[#f6fafe] transition-colors duration-200 group">
    <td class="{{ $pad }} py-4">
        <div class="flex items-center gap-3">
            @if($indent > 0)
            <span class="text-[#c2c6d6] material-symbols-outlined text-base shrink-0">subdirectory_arrow_right</span>
            @endif
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                {{ $indent === 0 ? 'bg-[#d8e2ff]' : 'bg-[#f0f4f8]' }}">
                <span class="material-symbols-outlined text-lg {{ $indent === 0 ? 'text-[#004395]' : 'text-[#727785]' }}">
                    {{ $indent === 0 ? 'domain' : 'subdirectory_arrow_right' }}
                </span>
            </div>
            <div>
                <p class="font-['Manrope'] font-{{ $indent === 0 ? 'bold' : 'semibold' }} text-[#171c1f] text-sm">{{ $area->nome }}</p>
                @if($area->descricao)
                <p class="text-xs text-[#727785] mt-0.5 line-clamp-1">{{ $area->descricao }}</p>
                @endif
                @if($area->children_count ?? $area->children->count())
                <p class="text-xs text-[#0058be] mt-0.5">
                    {{ $area->children_count ?? $area->children->count() }} subárea(s)
                </p>
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
