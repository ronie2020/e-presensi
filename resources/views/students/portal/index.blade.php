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

    <!-- GLOBAL FIXED BACKGROUND (Elegan, Tenang & Selaras dengan Landing & Login) -->
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        {{-- Base Canvas --}}
        <div class="absolute inset-0 bg-[#021124]"></div>

        {{-- Foto Gedung Sekolah Soft-Blur Ambient --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-10 filter blur-[14px] scale-110 contrast-125 saturate-50" 
             style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
        
        {{-- Radial Vignette & Dark Gradient Overlay --}}
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(13,82,161,0.2)_0%,_rgba(2,17,36,0.85)_50%,_#021124_100%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-[#021124] via-[#021124]/95 to-[#0d52a1]/25"></div>
        
        {{-- Cyber Grid Texture --}}
        <div class="absolute inset-0 bg-grid-dark opacity-35"></div>

        {{-- Ambient Glow Orbs --}}
        <div class="absolute -top-32 -left-32 w-[600px] h-[600px] bg-elevate-accent/15 rounded-full blur-[160px] pointer-events-none"></div>
        <div class="absolute top-1/3 right-1/4 w-[550px] h-[550px] bg-elevate-primary/25 rounded-full blur-[180px] pointer-events-none"></div>
        <div class="absolute -bottom-32 left-1/3 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[150px] pointer-events-none"></div>
    </div>

    <!-- 1. HERO SECTION & FORM (DARK GLASSMORPHISM) -->
    @include('students.portal.partials.home-hero')
    
</div>
@endsection