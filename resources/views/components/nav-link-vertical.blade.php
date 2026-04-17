@props(['active'])

@php
// Style Aktif: Gradient Cyan ke Biru, Teks Putih, Shadow Cyan, Sedikit lebih besar (scale)
// Style Inaktif: Transparan, Teks Biru Muda, Hover jadi background transparan putih terang
$classes = ($active ?? false)
            ? 'flex items-center px-5 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-2xl shadow-lg shadow-cyan-900/40 transition-all duration-300 font-bold transform scale-[1.02] relative overflow-hidden group'
            : 'flex items-center px-5 py-3.5 text-blue-100/80 hover:text-white hover:bg-white/10 rounded-2xl transition-all duration-200 font-medium group';

// Note: Variabel ini opsional jika Anda memasukkan icon langsung di dalam $slot,
// tapi berguna jika Anda memisahkan logic icon di dalam file ini.
$iconClasses = ($active ?? false)
            ? 'text-white mr-4 transition-colors duration-300'
            : 'text-cyan-300/70 group-hover:text-white mr-4 transition-colors duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes . ' mb-2']) }}>
    {{-- Slot ikon dan teks akan masuk di sini. 
         Tips: Pastikan icon SVG atau Phosphor Icon ukurannya proporsional --}}
    {{ $slot }}
    
    {{-- Indikator titik glowing kecil jika aktif (estetik tambahan) --}}
    @if($active ?? false)
        <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-cyan-200 shadow-[0_0_8px_rgba(165,243,252,0.8)]"></span>
    @endif
</a>