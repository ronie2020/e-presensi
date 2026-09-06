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
    // Semua nama class Tailwind ditulis literal (bukan hasil interpolasi string)
    // supaya tetap aman kalau proyek pindah dari Tailwind Play CDN ke build/purge biasa.
    $themes = [
        'blue'    => ['iconBg' => 'bg-blue-50',    'iconText' => 'text-blue-600',    'iconBgHover' => 'group-hover:bg-blue-100',    'labelHover' => 'group-hover:text-blue-600',    'borderStrong' => 'hover:border-blue-400',    'borderLight' => 'hover:border-blue-200',    'badgeBg' => 'bg-blue-500'],
        'emerald' => ['iconBg' => 'bg-emerald-50', 'iconText' => 'text-emerald-600', 'iconBgHover' => 'group-hover:bg-emerald-100', 'labelHover' => 'group-hover:text-emerald-600', 'borderStrong' => 'hover:border-emerald-400', 'borderLight' => 'hover:border-emerald-200', 'badgeBg' => 'bg-emerald-500'],
        'purple'  => ['iconBg' => 'bg-purple-50',  'iconText' => 'text-purple-600',  'iconBgHover' => 'group-hover:bg-purple-100',  'labelHover' => 'group-hover:text-purple-600',  'borderStrong' => 'hover:border-purple-400',  'borderLight' => 'hover:border-purple-200',  'badgeBg' => 'bg-purple-500'],
        'teal'    => ['iconBg' => 'bg-teal-50',    'iconText' => 'text-teal-600',    'iconBgHover' => 'group-hover:bg-teal-100',    'labelHover' => 'group-hover:text-teal-600',    'borderStrong' => 'hover:border-teal-400',    'borderLight' => 'hover:border-teal-200',    'badgeBg' => 'bg-teal-500'],
        'rose'    => ['iconBg' => 'bg-rose-50',    'iconText' => 'text-rose-600',    'iconBgHover' => 'group-hover:bg-rose-100',    'labelHover' => 'group-hover:text-rose-600',    'borderStrong' => 'hover:border-rose-400',    'borderLight' => 'hover:border-rose-200',    'badgeBg' => 'bg-rose-500'],
        'indigo'  => ['iconBg' => 'bg-indigo-50',  'iconText' => 'text-indigo-600',  'iconBgHover' => 'group-hover:bg-indigo-100',  'labelHover' => 'group-hover:text-indigo-600',  'borderStrong' => 'hover:border-indigo-400',  'borderLight' => 'hover:border-indigo-200',  'badgeBg' => 'bg-indigo-500'],
        'amber'   => ['iconBg' => 'bg-amber-50',   'iconText' => 'text-amber-600',   'iconBgHover' => 'group-hover:bg-amber-100',   'labelHover' => 'group-hover:text-amber-600',   'borderStrong' => 'hover:border-amber-400',   'borderLight' => 'hover:border-amber-200',   'badgeBg' => 'bg-amber-500'],
        'cyan'    => ['iconBg' => 'bg-cyan-50',    'iconText' => 'text-cyan-600',    'iconBgHover' => 'group-hover:bg-cyan-100',    'labelHover' => 'group-hover:text-cyan-600',    'borderStrong' => 'hover:border-cyan-400',    'borderLight' => 'hover:border-cyan-200',    'badgeBg' => 'bg-cyan-500'],
    ];

    $t = $themes[$theme] ?? $themes['blue'];

    // Kartu non-klik (mis. Total Siswa) memakai warna netral untuk angka & border hover yang lebih lembut.
    $valueColorClass = $clickable ? $t['iconText'] : 'text-slate-800';
    $borderClass = $clickable ? $t['borderStrong'] : $t['borderLight'];

    $cardClasses = 'bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 ' . $borderClass;
    $cardClasses .= $clickable
        ? ' hover:shadow-md cursor-pointer transition-all transform hover:-translate-y-1 group relative'
        : ' transition-colors';

    $hasTrend = !is_null($trend);
    $isGood = null;
    $isArrowUp = false;
    if ($hasTrend) {
        // Arah panah selalu mengikuti tanda tren yang sebenarnya (naik/turun),
        // sedangkan warna badge (hijau/merah) mengikuti apakah arah itu "baik" untuk metrik ini.
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
        <div class="absolute -top-3 right-0 {{ $t['badgeBg'] }} text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">Klik detail</div>
    @endif

    <div class="w-12 h-12 rounded-2xl {{ $t['iconBg'] }} {{ $t['iconText'] }} flex items-center justify-center text-xl {{ $clickable ? $t['iconBgHover'].' transition-colors' : '' }}">
        <i class="ph-fill ph-{{ $icon }}"></i>
    </div>

    <div class="flex flex-col items-center">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider {{ $clickable ? $t['labelHover'].' transition-colors' : '' }}">{{ $label }}</p>
        <div class="flex items-center gap-1.5">
            <p class="text-xl font-black {{ $valueColorClass }}">{{ $prefix }}{{ $value }}</p>
            @if($hasTrend)
                <span
                    class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border {{ $isGood ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}"
                    title="{{ $isGood ? $goodLabel : $badLabel }}"
                >
                    <i class="ph-bold {{ $isArrowUp ? 'ph-arrow-up-right' : 'ph-arrow-down-right' }} mr-0.5"></i> {{ abs($trend) }}%
                </span>
            @endif
        </div>
    </div>
</div>