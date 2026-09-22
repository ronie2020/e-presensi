@extends('layouts.public')

@section('title', 'Portal Data Akademik & Layanan Siswa - ' . config('app.name', 'SMP Negeri 3 Lakbok'))

@section('content')

{{-- STYLE KHUSUS HALAMAN PORTAL (ELEVATE DARK HARMONY) --}}
<style>
    [x-cloak] { display: none !important; }
    
    /* Paksa background body halaman ini menjadi Elevate Dark yang selaras */
    body {
        background-color: #021124 !important;
        color: #ffffff !important;
    }

    .bg-grid-dark {
        background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        background-size: 32px 32px;
    }

    @keyframes floatSlow {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }
    .animate-float-portal { animation: floatSlow 5s ease-in-out infinite; }
</style>

{{-- 
    DATA STATE:
    mode: 'portal' | 'lms' | 'cbt'
    isLoading: status loading saat submit form
--}}
<div class="w-full max-w-6xl mx-auto min-h-[85vh] flex flex-col justify-center px-4 sm:px-6 relative z-10 py-6 sm:py-10" 
     x-data="{ mode: 'portal', isLoading: false }">

    <!-- 1. HERO SECTION & FORM (DARK GLASSMORPHISM) -->
    @include('students.portal.partials.home-hero')
    
</div>
@endsection