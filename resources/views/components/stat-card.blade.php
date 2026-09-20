@props([
    'theme' => 'blue',            // blue, emerald, purple, teal, rose, indigo, amber, cyan
    'icon' => 'chart-bar',        // suffix ikon phosphor, mis. 'star', 'thermometer'
    'label' => '',
    'value' => 0,
    'prefix' => '',                // '+' , '-' , atau kosong
    'trend' => null,               // angka persen, atau null jika tidak ada tren
    'trendGoodDirection' => 'up',  // 'up' = makin tinggi makin baik, 'down' = makin rendah makin baik
    'trendGoodLabel' => null,      // tooltip saat tren berada di sisi baik
    'trendBadLabel' => null,       // tooltip saat tren berada di sisi buruk
    'clickable' => true,
    'type' => null,                // key modal drill-down, dipakai di onclick saat clickable
])

@php
    // Warna tema dengan saturasi tinggi & glowing background transparan untuk Elevate Dark
    $themes = [
        'blue'    => ['iconBg' => 'bg-sky-500/15',     'iconText' => 'text-sky-400',     'iconBgHover' => 'group-hover:bg-sky-500/25',     'labelHover' => 'group-hover:text-sky-300',     'borderStrong' => 'hover:border-sky-400/50',     'borderLight' => 'hover:border-sky-400/30',     'badgeBg' => 'bg-sky-500'],
        'emerald' => ['iconBg' => 'bg-emerald-500/15', 'iconText' => 'text-emerald-400', 'iconBgHover' => 'group-hover:bg-emerald-500/25', 'labelHover' => 'group-hover:text-emerald-300', 'borderStrong' => 'hover:border-emerald-400/50', 'borderLight' => 'hover:border-emerald-400/30', 'badgeBg' => 'bg-emerald-500'],
        'purple'  => ['iconBg' => 'bg-purple-500/15',  'iconText' => 'text-purple-400',  'iconBgHover' => 'group-hover:bg-purple-500/25',  'labelHover' => 'group-hover:text-purple-300',  'borderStrong' => 'hover:border-purple-400/50',  'borderLight' => 'hover:border-purple-400/30',  'badgeBg' => 'bg-purple-500'],
        'teal'    => ['iconBg' => 'bg-teal-500/15',    'iconText' => 'text-teal-400',    'iconBgHover' => 'group-hover:bg-teal-500/25',    'labelHover' => 'group-hover:text-teal-300',    'borderStrong' => 'hover:border-teal-400/50',    'borderLight' => 'hover:border-teal-400/30',    'badgeBg' => 'bg-teal-500'],
        'rose'    => ['iconBg' => 'bg-rose-500/15',    'iconText' => 'text-rose-400',    'iconBgHover' => 'group-hover:bg-rose-500/25',    'labelHover' => 'group-hover:text-rose-300',    'borderStrong' => 'hover:border-rose-400/50',    'borderLight' => 'hover:border-rose-400/30',    'badgeBg' => 'bg-rose-500'],
        'indigo'  => ['iconBg' => 'bg-indigo-500/15',  'iconText' => 'text-indigo-400',  'iconBgHover' => 'group-hover:bg-indigo-500/25',  'labelHover' => 'group-hover:text-indigo-300',  'borderStrong' => 'hover:border-indigo-400/50',  'borderLight' => 'hover:border-indigo-400/30',  'badgeBg' => 'bg-indigo-500'],
        'amber'   => ['iconBg' => 'bg-amber-500/15',   'iconText' => 'text-amber-400',   'iconBgHover' => 'group-hover:bg-amber-500/25',   'labelHover' => 'group-hover:text-amber-300',   'borderStrong' => 'hover:border-amber-400/50',   'borderLight' => 'hover:border-amber-400/30',   'badgeBg' => 'bg-amber-500'],
        'cyan'    => ['iconBg' => 'bg-cyan-500/15',    'iconText' => 'text-cyan-400',    'iconBgHover' => 'group-hover:bg-cyan-500/25',    'labelHover' => 'group-hover:text-cyan-300',    'borderStrong' => 'hover:border-cyan-400/50',    'borderLight' => 'hover:border-cyan-400/30',    'badgeBg' => 'bg-cyan-500'],
    ];

    $t = $themes[$theme] ?? $themes['blue'];

    $valueColorClass = $clickable ? $t['iconText'] : 'text-white';
    $borderClass = $clickable ? $t['borderStrong'] : $t['borderLight'];

    $cardClasses = 'bg-[#031d3d]/80 backdrop-blur-xl p-4 rounded-3xl border border-white/10 shadow-lg shadow-black/30 flex flex-col items-center text-center gap-2 ' . $borderClass;
    $cardClasses .= $clickable
        ? ' hover:shadow-2xl hover:shadow-sky-500/10 cursor-pointer transition-all transform hover:-translate-y-1 group relative'
        : ' transition-colors';

    $hasTrend = !is_null($trend);
    $isGood = null;
    $isArrowUp = false;
    if ($hasTrend) {
        $isArrowUp = $trend >= 0;
        $isGood = $trendGoodDirection === 'down' ? $trend <= 0 : $trend >= 0;
    }
    $goodLabel = $trendGoodLabel ?? 'Naik dari periode sebelumnya';
    $badLabel = $trendBadLabel ?? 'Turun dari periode sebelumnya';
@endphp

<div
    @if($clickable) onclick="openDrilldownModal('{{ $type }}')" @endif
    class="{{ $cardClasses }}"
>
    @if($clickable)
        <div class="absolute -top-2.5 right-2 {{ $t['badgeBg'] }} text-white text-[9px] font-black px-2.5 py-0.5 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity uppercase tracking-wider">Detail</div>
    @endif

    <div class="w-12 h-12 rounded-2xl {{ $t['iconBg'] }} {{ $t['iconText'] }} border border-white/10 flex items-center justify-center text-xl shadow-inner {{ $clickable ? $t['iconBgHover'].' transition-colors' : '' }}">
        <i class="ph-fill ph-{{ $icon }}"></i>
    </div>

    <div class="flex flex-col items-center">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider {{ $clickable ? $t['labelHover'].' transition-colors' : '' }}">{{ $label }}</p>
        <div class="flex items-center gap-1.5 mt-0.5">
            <p class="text-xl font-black {{ $valueColorClass }}">{{ $prefix }}{{ $value }}</p>
            @if($hasTrend)
                <span
                    class="text-[9px] font-bold px-1.5 py-0.5 rounded-md flex items-center border {{ $isGood ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border-rose-500/30' }}"
                    title="{{ $isGood ? $goodLabel : $badLabel }}"
                >
                    <i class="ph-bold {{ $isArrowUp ? 'ph-arrow-up-right' : 'ph-arrow-down-right' }} mr-0.5"></i> {{ abs($trend) }}%
                </span>
            @endif
        </div>
    </div>
</div>