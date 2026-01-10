@extends('layouts.public')

@section('content')

{{-- STYLE KHUSUS HALAMAN INI --}}
<style>
    [x-cloak] { display: none !important; }
    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
    /* Mengurangi animasi jika user mengaktifkan "Reduced Motion" di OS */
    @media (prefers-reduced-motion: reduce) {
        .animate-blob { animation: none; }
    }
</style>

{{-- 
    DATA STATE:
    mode: 'portal' | 'lms' | 'cbt'
    isLoading: status loading saat submit form
--}}
<div class="w-full max-w-6xl mx-auto min-h-[85vh] flex flex-col justify-center px-4" 
     x-data="{ mode: 'portal', isLoading: false }">

    <!-- 1. HERO SECTION (DYNAMIC THEME) -->
    @include('students.portal.partials.home-hero')
    
</div>
@endsection