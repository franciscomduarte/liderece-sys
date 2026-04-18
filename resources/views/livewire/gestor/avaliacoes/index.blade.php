@section('page-title', 'Avaliações da Área')
@section('page-subtitle', 'Acompanhe e avalie os servidores da sua equipe')

<div>
<div class="space-y-6">

    {{-- Sem ciclo ativo --}}
    @if(! $ciclo)
    <div class="bg-white rounded-2xl p-16 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
        <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">event_busy</span>
        <p class="font-['Manrope'] font-bold text-[#171c1f] mb-1">Nenhum ciclo ativo</p>
        <p class="text-sm text-[#727785]">As avaliações ficam disponíveis quando um ciclo é ativado.</p>
    </div>
    @else

    {{-- Banner + Filtro --}}
    <div class="flex items-center gap-4 flex-wrap">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3 bg-[#00855b]/10 border border-[#006947]/20 rounded-xl px-4 py-3">
                <span class="material-symbols-outlined text-[#006947] text-lg">play_circle</span>
                <div>
                    <p class="text-[#006947] font-['Manrope'] font-bold text-sm">{{ $ciclo->nome }}</p>
                    <p class="text-[#005236] text-xs">{{ $ciclo->data_inicio->format('d/m/Y') }} → {{ $ciclo->data_fim->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
        @if($servidores->count() > 1)
        <div class="shrink-0">
            <select wire:model.live="filtroServidor" class="px-3.5 py-2.5 border border-[#c2c6d6] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0058be]/30 focus:border-[#0058be] bg-white">
                <option value="">Todos os servidores</option>
                @foreach($servidores as $s)
                <option value="{{ $s->id }}">{{ $s->nome }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    @if($servidores->isEmpty())
    <div class="bg-white rounded-2xl p-16 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
        <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">group</span>
        <p class="font-['Manrope'] font-bold text-[#171c1f] mb-1">Nenhum servidor na área</p>
        <p class="text-sm text-[#727785]">Adicione servidores à sua área para avaliar.</p>
    </div>
    @elseif($competencias->isEmpty())
    <div class="bg-white rounded-2xl p-16 shadow-[0_12px_40px_rgba(23,28,31,0.06)] text-center">
        <span class="material-symbols-outlined text-4xl text-[#c2c6d6] block mb-3">psychology</span>
        <p class="font-['Manrope'] font-bold text-[#171c1f] mb-1">Nenhuma competência configurada</p>
        <p class="text-sm text-[#727785]">Solicite ao administrador que vincule competências à sua área.</p>
    </div>
    @else

    {{-- Tabela --}}
    <div class="float-in bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.06)] ring-1 ring-black/[0.04] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#f0f4f8]">
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Servidor</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Competência</th>
                        <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Autoavaliação</th>
                        <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Minha avaliação</th>
                        <th class="text-right px-5 py-3 text-xs font-bold uppercase tracking-widest text-[#424754]">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#eaeef2]">
                    @foreach($servidores as $servidor)
                    @foreach($competencias as $competencia)
                    @php
                        $auto = $autoavaliacoes[$servidor->id][$competencia->id] ?? null;
                        $gestorAv = $gestorAvaliacoes[$servidor->id][$competencia->id] ?? null;
                    @endphp
                    <tr class="hover:bg-[#f6fafe] transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-[#d8e2ff] flex items-center justify-center shrink-0">
                                    <span class="text-xs font-bold text-[#004395]">{{ substr($servidor->nome, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-[#171c1f]">{{ $servidor->nome }}</p>
                                    <p class="text-xs text-[#727785]">{{ $servidor->cargo }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div>
                                <p class="text-sm font-medium text-[#171c1f]">{{ $competencia->nome }}</p>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold
                                    @if($competencia->tipo === 'comportamental') bg-[#d8e2ff] text-[#004395]
                                    @elseif($competencia->tipo === 'técnica') bg-[#6ffbbe]/40 text-[#002113]
                                    @else bg-[#dee2f7] text-[#414657] @endif">
                                    {{ ucfirst($competencia->tipo) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($auto && $auto->status === 'enviada')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold bg-[#00855b]/10 text-[#006947]">
                                <span class="material-symbols-outlined text-sm">check</span>
                                {{ number_format($auto->media, 1) }}
                            </span>
                            @elseif($auto && $auto->status === 'rascunho')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Rascunho
                            </span>
                            @else
                            <span class="text-xs text-[#c2c6d6]">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($gestorAv && $gestorAv->status === 'enviada')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold bg-[#00855b]/10 text-[#006947]">
                                <span class="material-symbols-outlined text-sm">check</span>
                                {{ number_format($gestorAv->media, 1) }}
                            </span>
                            @elseif($gestorAv && $gestorAv->status === 'rascunho')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Rascunho
                            </span>
                            @else
                            <span class="text-xs text-[#c2c6d6]">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            @if($gestorAv && $gestorAv->status === 'enviada')
                            <a href="{{ route('gestor.avaliacoes.form', $gestorAv) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#f0f4f8] text-[#424754] hover:bg-[#e4e9ed] transition-colors">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                                Ver
                            </a>
                            @elseif($gestorAv && $gestorAv->status === 'rascunho')
                            <a href="{{ route('gestor.avaliacoes.form', $gestorAv) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors">
                                <span class="material-symbols-outlined text-sm">edit</span>
                                Continuar
                            </a>
                            @else
                            <button wire:click="iniciarAvaliacao('{{ $servidor->id }}', '{{ $competencia->id }}')"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-br from-[#0058be] to-[#2170e4] text-white shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all">
                                <span class="material-symbols-outlined text-sm">rate_review</span>
                                Avaliar
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @endif
    @endif

</div>
</div>
