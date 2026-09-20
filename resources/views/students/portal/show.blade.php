@extends('layouts.public')

@section('content')
{{-- SET LOCALE --}}
@php
    \Carbon\Carbon::setLocale('id');
    $isAlumni = $student->status === 'graduated';
@endphp

<style>
    /* Menyembunyikan scrollbar tapi tetap bisa discroll */
    .custom-scrollbar::-webkit-scrollbar { height: 0px; background: transparent; }
    .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
    .ph-fill, .ph-duotone, .ph-bold { vertical-align: middle; }
    
    /* Animasi transisi yang lebih halus dan elegan untuk tab */
    .tab-content-enter {
        animation: slideFadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes slideFadeIn {
        from { opacity: 0; transform: translateY(20px); filter: blur(4px); }
        to { opacity: 1; transform: translateY(0); filter: blur(0); }
    }
    
    /* Efek glass untuk container konten */
    .content-glass-wrapper {
        background: rgba(3, 29, 61, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 1.75rem;
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.5); 
        transition: all 0.5s ease;
        color: #ffffff;
    }

    html.dark .content-glass-wrapper {
        background: rgba(3, 29, 61, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.6); 
    }
</style>

<!-- X-DATA: Main Controller + DARK MODE LOGIC -->
<div class="w-full max-w-6xl mx-auto pb-20 px-4 sm:px-6 min-h-screen relative z-10"
     x-data="{ 
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'ringkasan',
        isTransitioning: false,
        
        // --- LOGIKA DARK MODE ---
        isDark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        
        init() {
            this.applyTheme();
            this.$watch('isDark', value => this.applyTheme());
        },
        
        toggleTheme() {
            this.isDark = !this.isDark;
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        },
        
        applyTheme() {
            if (this.isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        // ------------------------

        updateTab(val) {
            if(this.activeTab === val || this.isTransitioning) return;
            
            this.isTransitioning = true;
            this.activeTab = val;
            
            // Update URL tanpa reload
            const url = new URL(window.location);
            url.searchParams.set('tab', val);
            window.history.pushState({}, '', url);
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // TRIGGER PENTING UNTUK FULLCALENDAR
            window.dispatchEvent(new CustomEvent('tab-changed', { detail: { tab: val } }));
            
            setTimeout(() => { 
                window.dispatchEvent(new Event('resize'));
                this.isTransitioning = false;
            }, 300);
        }
     }">
    
    {{-- Latar Belakang Fixed Elevate Dark --}}
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden bg-[#021124]">
        {{-- Foto Gedung Sekolah Soft-Blur Ambient --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-10 filter blur-[14px] scale-110 contrast-125 saturate-50" 
             style="background-image: url('{{ asset('images/netila.jpg') }}');"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(13,82,161,0.2)_0%,_rgba(2,17,36,0.85)_50%,_#021124_100%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-[#021124] via-[#021124]/95 to-[#0d52a1]/25"></div>
        
        <!-- Ambient Globs -->
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-elevate-accent/15 rounded-full blur-[140px] pointer-events-none"></div>
        <div class="absolute top-1/2 -left-40 w-[600px] h-[600px] bg-elevate-primary/20 rounded-full blur-[160px] pointer-events-none"></div>
        <div class="absolute -bottom-40 right-20 w-[400px] h-[400px] bg-elevate-accent/10 rounded-full blur-[130px] pointer-events-none"></div>
        
        <!-- Texture Overlay (Cubes) -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.04] mix-blend-overlay"></div>
    </div>

    {{-- 1. HEADER PROFIL --}}
    @include('students.portal.partials.header')

    {{-- 2. NAVIGATION TABS --}}
    @include('students.portal.partials.tabs-nav')

    {{-- 3. CONTENT AREAS --}}
    <div class="min-h-[400px] relative mt-4">
        
        <!-- Tab Ringkasan -->
        <div x-show="activeTab === 'ringkasan'" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="tab-content-enter">
            @include('students.portal.partials.tab-ringkasan')
        </div>

        @if(!$isAlumni)
            <!-- Tab 7 Kebiasaan -->
            <div x-show="activeTab === 'kebiasaan'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-kebiasaan')
            </div>

            <!-- Tab Jurnal Literasi Mandiri -->
            <div x-show="activeTab === 'literasi_mandiri'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-literasi-mandiri')
            </div>

            <!-- Tab Buku Penghubung -->
            <div x-show="activeTab === 'penghubung'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-penghubung')
            </div>
            
            <!-- Tab E-COUNSELING (BK) -->
            <div x-show="activeTab === 'bk'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-bk')
            </div>

            <!-- Tab Pengaduan -->
            <div x-show="activeTab === 'pengaduan'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-pengaduan')
            </div>

            <!-- Tab Jadwal -->
            <div x-show="activeTab === 'jadwal'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-jadwal')
            </div>

            <!-- Tab LMS (Tugas) -->
            <div x-show="activeTab === 'lms'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-lms')
            </div>

            <!-- Tab Jurnal KBM -->
            <div x-show="activeTab === 'kbm'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-kbm')
            </div>

            <!-- Tab Akademik (Nilai) -->
            <div x-show="activeTab === 'akademik'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-akademik')
            </div>

            <!-- Tab Kehadiran -->
            <div x-show="activeTab === 'kehadiran'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-kehadiran')
            </div>

            <!-- Tab Disiplin -->
            <div x-show="activeTab === 'disiplin'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-disiplin')
            </div>

            <!-- Tab Keagamaan -->
            <div x-show="activeTab === 'keagamaan'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-keagamaan')
            </div>
            
            <!-- Tab Ramadan Jurnal -->   
            <div x-show="activeTab === 'ramadan_jurnal'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-ramadan-jurnal')
            </div>
            
            <!-- Tab Leaderboard Ramadhan -->
            <div x-show="activeTab === 'ramadan_rank'" x-cloak 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                @include('students.portal.partials.tab-ramadan-leaderboard')
            </div>
        @endif
        
        <!-- Tab Prestasi (Alumni & Siswa) -->
        <div x-show="activeTab === 'prestasi'" x-cloak 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            @include('students.portal.partials.tab-prestasi')
        </div>

        <!-- Tab Perpustakaan (Alumni & Siswa) -->
        <div x-show="activeTab === 'perpustakaan'" x-cloak 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            @include('students.portal.partials.tab-perpustakaan')
        </div>

    </div>
</div>

{{-- 4. SCRIPTS --}}
@include('students.portal.partials.scripts')

@endsection