@php
    $servidor = auth()->user()?->servidor;
    $naoLidas = $servidor?->notificacoes()->where('lida', false)->count() ?? 0;
@endphp

<header class="bg-white border-b border-[#eaeef2] px-4 md:px-8 py-4 flex items-center justify-between sticky top-0 z-40 shadow-[0_2px_8px_rgba(23,28,31,0.04)]">

    {{-- Título da página --}}
    <div>
        <h1 class="text-[#171c1f] font-['Manrope'] font-bold text-lg">@yield('page-title', 'Dashboard')</h1>
        <p class="text-[#424754] text-xs mt-0.5">@yield('page-subtitle', '')</p>
    </div>

    {{-- Ações à direita --}}
    <div class="flex items-center gap-3">

        {{-- Notificações --}}
        @if($servidor)
        <div class="relative" x-data="{ open: false }">
            <button
                @click="open = !open"
                class="relative w-10 h-10 rounded-xl bg-[#f0f4f8] hover:bg-[#eaeef2] flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[#424754] text-xl">notifications</span>
                @if($naoLidas > 0)
                <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-[#ba1a1a] text-white text-[10px] font-bold flex items-center justify-center">
                    {{ $naoLidas > 9 ? '9+' : $naoLidas }}
                </span>
                @endif
            </button>

            {{-- Dropdown de notificações --}}
            <div
                x-show="open"
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-[0_12px_40px_rgba(23,28,31,0.12)] border border-[#eaeef2] overflow-hidden z-50"
                style="display: none;"
            >
                <div class="px-4 py-3 border-b border-[#eaeef2] flex items-center justify-between">
                    <span class="font-['Manrope'] font-bold text-sm text-[#171c1f]">Notificações</span>
                    @if($naoLidas > 0)
                    <span class="text-xs text-[#0058be] font-semibold">{{ $naoLidas }} não lidas</span>
                    @endif
                </div>
                <div class="max-h-64 overflow-y-auto">
                    @forelse($servidor->notificacoes()->latest()->take(10)->get() as $notif)
                    <a href="{{ $notif->link ?? '#' }}"
                       class="flex items-start gap-3 px-4 py-3 hover:bg-[#f0f4f8] transition-colors border-b border-[#f0f4f8] last:border-0
                           {{ !$notif->lida ? 'bg-[#f6fafe]' : '' }}">
                        <div class="w-8 h-8 rounded-full bg-[#d8e2ff] flex items-center justify-center shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-[#004395] text-sm">notifications</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-[#171c1f] truncate">{{ $notif->titulo }}</p>
                            @if($notif->descricao)
                            <p class="text-xs text-[#424754] mt-0.5 line-clamp-2">{{ $notif->descricao }}</p>
                            @endif
                            <p class="text-[10px] text-[#727785] mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notif->lida)
                        <div class="w-2 h-2 rounded-full bg-[#0058be] shrink-0 mt-2"></div>
                        @endif
                    </a>
                    @empty
                    <div class="px-4 py-8 text-center">
                        <span class="material-symbols-outlined text-3xl text-[#c2c6d6]">notifications_off</span>
                        <p class="text-sm text-[#727785] mt-2">Nenhuma notificação</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        {{-- Avatar / nome do usuário --}}
        @if($servidor)
        <div class="hidden md:flex items-center gap-2 pl-3 border-l border-[#eaeef2]">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#0058be] to-[#2170e4] flex items-center justify-center">
                <span class="text-white text-xs font-bold">{{ strtoupper(substr($servidor->nome, 0, 2)) }}</span>
            </div>
            <span class="text-sm font-semibold text-[#171c1f]">{{ explode(' ', $servidor->nome)[0] }}</span>
        </div>
        @endif
    </div>
</header>
