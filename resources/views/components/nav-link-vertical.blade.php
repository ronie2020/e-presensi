@props(['active'])

@php
// Style Aktif: Background Putih, Teks Biru, Shadow, Sedikit lebih besar (scale)
// Style Inaktif: Transparan, Teks Biru Muda, Hover jadi agak terang
$classes = ($active ?? false)
            ? 'flex items-center px-5 py-3.5 bg-white text-blue-700 rounded-2xl shadow-lg shadow-blue-900/20 transition-all duration-300 font-bold transform scale-[1.02] relative overflow-hidden group'
            : 'flex items-center px-5 py-3.5 text-blue-100/80 hover:text-white hover:bg-white/10 rounded-2xl transition-all duration-200 font-medium group';

$iconClasses = ($active ?? false)
            ? 'text-blue-600 mr-4 transition-colors duration-300'
            : 'text-blue-300/70 group-hover:text-white mr-4 transition-colors duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes . ' mb-2']) }}>
    {{-- Slot ikon dan teks akan masuk di sini. 
         Tips: Pastikan icon SVG di navigation.blade.php ukurannya w-6 h-6 agar proporsional --}}
    {{ $slot }}
    
    {{-- Indikator panah kecil jika aktif (opsional, estetik tambahan) --}}
    @if($active ?? false)
        <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-blue-500"></span>
    @endif
</a>