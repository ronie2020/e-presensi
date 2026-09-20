@props([
    'badge' => null,
    'badgeIcon' => null,
    'title' => null,
    'titleHighlight' => null,
    'description' => null,
    'chips' => [],
    'showcaseValue' => null,
    'showcaseLabel' => null,
    'showcaseIcon' => 'chart-pie',
    'showcaseStatus' => null,
    'showcaseBubbles' => []
])

<div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden group">
    {{-- Specular Top Rim Light (Ref 2) --}}
    <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none border-t border-l border-white/30"></div>
    <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>

    {{-- Ambient Radiant Glow Orbs (Ref 1 & 2) --}}
    <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#56bbf1]/20 rounded-full blur-[100px] pointer-events-none no-print"></div>
    <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-[#0d52a1]/30 rounded-full blur-[90px] pointer-events-none no-print"></div>
    
    <div class="relative z-10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-8">
        {{-- KIRI: Judul, Deskripsi, Chips, Actions --}}
        <div class="space-y-4 max-w-2xl">
            @if(isset($headerNav))
                {{ $headerNav }}
            @elseif($badge)
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white border border-white/15 text-xs font-bold transition-all shadow-sm">
                        <i class="ph-bold ph-arrow-left text-sky-400"></i>
                        <span>Dashboard</span>
                    </a>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm">
                        @if($badgeIcon)
                            <i class="ph-fill ph-{{ $badgeIcon }} text-sky-400"></i>
                        @else
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-400"></span>
                            </span>
                        @endif
                        {{ $badge }}
                    </div>
                </div>
            @endif

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-snug sm:leading-snug md:leading-normal text-white">
                @if($title)
                    <span class="block text-slate-100">{{ $title }}</span>
                @endif
                @if($titleHighlight)
                    <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">{{ $titleHighlight }}</span>
                @endif
            </h1>

            @if($description)
                <p class="text-slate-300 text-sm sm:text-base font-medium leading-relaxed max-w-xl">
                    {{ $description }}
                </p>
            @endif

            {{-- Feature Chips (Ref 1 & 2) --}}
            @if(isset($chipsSlot))
                {{ $chipsSlot }}
            @elseif(!empty($chips))
                <div class="flex flex-wrap items-center gap-2.5 pt-1">
                    @foreach($chips as $chip)
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                            <i class="ph-bold ph-check text-sky-400"></i> {{ $chip }}
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Dual / Multiple Actions --}}
            @if(isset($actions))
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    {{ $actions }}
                </div>
            @endif
        </div>

        {{-- KANAN: Luminous Circular Showcase (Ref 1 & 2) --}}
        @if(isset($showcase))
            {{ $showcase }}
        @elseif($showcaseValue !== null || $showcaseLabel !== null)
            <div class="relative flex items-center justify-center shrink-0 w-full xl:w-auto mt-4 xl:mt-0">
                <div class="absolute w-52 h-52 rounded-full border border-[#56bbf1]/30 animate-pulse pointer-events-none"></div>
                <div class="absolute w-60 h-60 rounded-full border border-sky-400/15 pointer-events-none"></div>

                <div class="relative z-10 w-44 h-44 rounded-full bg-gradient-to-br from-[#0d52a1]/80 via-[#031d3d]/90 to-[#021124] p-1 border-2 border-[#56bbf1]/50 shadow-2xl shadow-sky-500/30 backdrop-blur-xl flex flex-col items-center justify-center text-center">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-sky-400 to-[#0d52a1] flex items-center justify-center text-white shadow-lg shadow-sky-400/40 mb-1 border border-white/20">
                        <i class="ph-bold ph-{{ $showcaseIcon }} text-xl"></i>
                    </div>
                    @if($showcaseValue !== null)
                        <span class="text-3xl font-black tracking-tight text-white leading-none">
                            {{ $showcaseValue }}
                        </span>
                    @endif
                    @if($showcaseLabel)
                        <span class="text-[10px] font-bold text-sky-300 uppercase tracking-widest mt-1">{{ $showcaseLabel }}</span>
                    @endif
                    @if($showcaseStatus)
                        <div class="mt-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[10px] font-bold">
                            <i class="ph-fill ph-check-circle"></i> {{ $showcaseStatus }}
                        </div>
                    @endif
                </div>

                @if(!empty($showcaseBubbles))
                    @foreach($showcaseBubbles as $bubble)
                        <div class="absolute {{ $bubble['pos'] ?? '-top-2 -right-2' }} z-20 flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#031d3d]/90 border border-white/20 text-white text-[11px] font-bold shadow-xl backdrop-blur-md">
                            @if(isset($bubble['icon']))
                                <i class="ph-bold ph-{{ $bubble['icon'] }} {{ $bubble['iconColor'] ?? 'text-sky-400' }}"></i>
                            @endif
                            <span>{{ $bubble['label'] ?? '' }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        @endif
    </div>
</div>
