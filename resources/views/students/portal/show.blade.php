@extends('layouts.public')

@section('content')
{{-- SET LOCALE --}}
@php
    \Carbon\Carbon::setLocale('id');
    $isAlumni = $student->status === 'graduated';
@endphp

<style>
    /* Menyembunyikan scrollbar tapi tetap bisa discroll */
    .custom-scrollbar::-webkit-scrollbar { height: 0px; width: 0px; background: transparent; }
    .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
    .ph-fill, .ph-duotone, .ph-bold { vertical-align: middle; }
    
    /* Animasi transisi yang lebih halus dan elegan untuk tab */
    .tab-content-enter {
        animation: slideFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes slideFadeIn {
        from { opacity: 0; transform: translateY(16px); filter: blur(4px); }
        to { opacity: 1; transform: translateY(0); filter: blur(0); }
    }
    
    /* ==========================================================================
       TRANSFORMASI GLOBAL ESTETIKA ELEVATE DARK GLASSMORPHISM UNTUK TAB PORTAL
       Menyelaraskan seluruh tab di dalam portal dengan desain utama website
       ========================================================================== */
    #portal-content-wrapper .bg-white:not(button):not(a),
    #portal-content-wrapper .bg-white\/90:not(button):not(a),
    #portal-content-wrapper .bg-white\/80:not(button):not(a),
    #portal-content-wrapper .dark\:bg-slate-800,
    #portal-content-wrapper .dark\:bg-slate-800\/80 {
        background-color: rgba(3, 29, 61, 0.85) !important;
        backdrop-filter: blur(24px) !important;
        -webkit-backdrop-filter: blur(24px) !important;
        border-color: rgba(255, 255, 255, 0.14) !important;
        color: #ffffff !important;
        box-shadow: 0 15px 40px -10px rgba(0, 0, 0, 0.45) !important;
    }

    /* Fix khusus tombol & link dengan class bg-white di dalam portal agar tidak bentrok dengan app.css (dark-on-dark) */
    #portal-content-wrapper button.bg-white,
    #portal-content-wrapper a.bg-white {
        background-color: rgba(13, 82, 161, 0.45) !important;
        border: 1px solid rgba(86, 187, 241, 0.4) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.3) !important;
    }
    #portal-content-wrapper button.bg-white:hover,
    #portal-content-wrapper a.bg-white:hover {
        background-color: #0d52a1 !important;
        border-color: #56bbf1 !important;
        color: #ffffff !important;
    }
    #portal-content-wrapper button.bg-white *,
    #portal-content-wrapper a.bg-white * {
        color: #ffffff !important;
    }

    #portal-content-wrapper .text-elevate-dark,
    #portal-content-wrapper .text-slate-800,
    #portal-content-wrapper .text-slate-900,
    #portal-content-wrapper .text-slate-700,
    #portal-content-wrapper .text-\[\#2A3B52\] {
        color: #ffffff !important;
    }

    #portal-content-wrapper .text-slate-500,
    #portal-content-wrapper .text-slate-400 {
        color: #94a3b8 !important;
    }

    #portal-content-wrapper .text-slate-600 {
        color: #cbd5e1 !important;
    }

    #portal-content-wrapper .border-slate-50,
    #portal-content-wrapper .border-slate-100,
    #portal-content-wrapper .border-slate-200,
    #portal-content-wrapper .border-slate-300,
    #portal-content-wrapper .divide-slate-50 > * + *,
    #portal-content-wrapper .divide-slate-100 > * + *,
    #portal-content-wrapper .divide-slate-200 > * + * {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    #portal-content-wrapper .bg-slate-50,
    #portal-content-wrapper .bg-slate-50\/50,
    #portal-content-wrapper .bg-slate-50\/30,
    #portal-content-wrapper .bg-slate-100 {
        background-color: rgba(2, 17, 36, 0.7) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #e2e8f0 !important;
    }

    #portal-content-wrapper .bg-slate-200 {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }

    #portal-content-wrapper .bg-elevate-soft,
    #portal-content-wrapper .bg-elevate-soft\/50,
    #portal-content-wrapper .bg-elevate-soft\/30 {
        background-color: rgba(56, 189, 248, 0.15) !important;
        color: #38bdf8 !important;
        border-color: rgba(56, 189, 248, 0.3) !important;
    }

    /* Override warna pastel terang (rose-50, emerald-50, amber-50, blue-50, dll) agar selaras dengan Dark Mode */
    #portal-content-wrapper .bg-rose-50,
    #portal-content-wrapper .bg-rose-50\/30,
    #portal-content-wrapper .bg-rose-50\/50,
    #portal-content-wrapper .bg-rose-100,
    #portal-content-wrapper .bg-\[\#FDE7E9\] {
        background-color: rgba(244, 63, 94, 0.15) !important;
        border-color: rgba(244, 63, 94, 0.3) !important;
    }
    #portal-content-wrapper .bg-emerald-50,
    #portal-content-wrapper .bg-emerald-50\/50,
    #portal-content-wrapper .bg-emerald-100,
    #portal-content-wrapper .bg-\[\#DFF6DD\] {
        background-color: rgba(16, 185, 129, 0.15) !important;
        border-color: rgba(16, 185, 129, 0.3) !important;
    }
    #portal-content-wrapper .bg-amber-50,
    #portal-content-wrapper .bg-amber-50\/50,
    #portal-content-wrapper .bg-amber-100,
    #portal-content-wrapper .bg-yellow-50,
    #portal-content-wrapper .bg-yellow-100,
    #portal-content-wrapper .bg-orange-50,
    #portal-content-wrapper .bg-orange-100,
    #portal-content-wrapper .bg-\[\#FFEFD6\] {
        background-color: rgba(245, 158, 11, 0.15) !important;
        border-color: rgba(245, 158, 11, 0.3) !important;
    }
    #portal-content-wrapper .bg-blue-50,
    #portal-content-wrapper .bg-blue-50\/50,
    #portal-content-wrapper .bg-blue-100,
    #portal-content-wrapper .bg-sky-50,
    #portal-content-wrapper .bg-sky-100,
    #portal-content-wrapper .bg-indigo-50,
    #portal-content-wrapper .bg-indigo-100,
    #portal-content-wrapper .bg-\[\#F3F9FD\] {
        background-color: rgba(56, 189, 248, 0.15) !important;
        border-color: rgba(56, 189, 248, 0.3) !important;
    }
    #portal-content-wrapper .bg-purple-50,
    #portal-content-wrapper .bg-purple-50\/50,
    #portal-content-wrapper .bg-purple-100,
    #portal-content-wrapper .bg-violet-50,
    #portal-content-wrapper .bg-violet-100,
    #portal-content-wrapper .bg-pink-50,
    #portal-content-wrapper .bg-pink-100 {
        background-color: rgba(168, 85, 247, 0.15) !important;
        border-color: rgba(168, 85, 247, 0.3) !important;
    }

    /* Penyesuaian Teks Warna gelap agar tetap kontras di atas kartu gelap */
    #portal-content-wrapper .text-rose-900,
    #portal-content-wrapper .text-rose-800,
    #portal-content-wrapper .text-rose-700,
    #portal-content-wrapper .text-rose-600,
    #portal-content-wrapper .text-\[\#D13438\] {
        color: #fda4af !important;
    }
    #portal-content-wrapper .text-emerald-900,
    #portal-content-wrapper .text-emerald-800,
    #portal-content-wrapper .text-emerald-700,
    #portal-content-wrapper .text-emerald-600,
    #portal-content-wrapper .text-\[\#107C10\] {
        color: #6ee7b7 !important;
    }
    #portal-content-wrapper .text-amber-900,
    #portal-content-wrapper .text-amber-800,
    #portal-content-wrapper .text-amber-700,
    #portal-content-wrapper .text-amber-600,
    #portal-content-wrapper .text-orange-700,
    #portal-content-wrapper .text-orange-600,
    #portal-content-wrapper .text-\[\#D83B01\] {
        color: #fcd34d !important;
    }
    #portal-content-wrapper .text-blue-900,
    #portal-content-wrapper .text-blue-800,
    #portal-content-wrapper .text-blue-700,
    #portal-content-wrapper .text-blue-600,
    #portal-content-wrapper .text-sky-900,
    #portal-content-wrapper .text-sky-800,
    #portal-content-wrapper .text-sky-700,
    #portal-content-wrapper .text-elevate-primary {
        color: #7dd3fc !important;
    }
    #portal-content-wrapper .text-purple-700,
    #portal-content-wrapper .text-purple-600,
    #portal-content-wrapper .text-violet-700,
    #portal-content-wrapper .text-violet-600 {
        color: #d8b4fe !important;
    }

    /* Form Controls & Inputs */
    #portal-content-wrapper select,
    #portal-content-wrapper input[type="text"],
    #portal-content-wrapper input[type="date"],
    #portal-content-wrapper input[type="number"],
    #portal-content-wrapper input[type="file"],
    #portal-content-wrapper textarea {
        background-color: rgba(2, 17, 36, 0.85) !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
    }

    #portal-content-wrapper select option {
        background-color: #021124 !important;
        color: #ffffff !important;
    }

    /* Tables */
    #portal-content-wrapper table {
        color: #e2e8f0 !important;
    }

    #portal-content-wrapper thead tr,
    #portal-content-wrapper th {
        background-color: rgba(2, 17, 36, 0.9) !important;
        color: #38bdf8 !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
    }

    #portal-content-wrapper td {
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    #portal-content-wrapper tbody tr:hover td {
        background-color: rgba(255, 255, 255, 0.04) !important;
    }

    /* FullCalendar Dark Theme Fixes */
    #portal-content-wrapper .fc-theme-standard td, 
    #portal-content-wrapper .fc-theme-standard th {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    #portal-content-wrapper .fc-col-header-cell-cushion, 
    #portal-content-wrapper .fc-daygrid-day-number {
        color: #e2e8f0 !important;
    }
    #portal-content-wrapper .fc-scrollgrid {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    #portal-content-wrapper .fc-day-today {
        background-color: rgba(56, 189, 248, 0.08) !important;
    }
    #portal-content-wrapper .fc-toolbar-title {
        color: #ffffff !important;
    }
    #portal-content-wrapper .fc-button-primary {
        background: rgba(3, 29, 61, 0.9) !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }
    #portal-content-wrapper .fc-button-primary:hover {
        background: #0d52a1 !important;
    }
</style>

<!-- X-DATA: Main Controller + DARK MODE LOGIC -->
<div id="portal-content-wrapper" class="w-full max-w-6xl mx-auto pb-20 px-4 sm:px-6 min-h-screen relative z-10 font-sans"
     x-data="{ 
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'ringkasan',
        isTransitioning: false,
        
        // --- LOGIKA DARK MODE (Selalu Aktif untuk Tema Elevate Dark Glassmorphism) ---
        isDark: true,
        
        init() {
            document.documentElement.classList.add('dark');
        },
        
        toggleTheme() {
            this.isDark = true;
            document.documentElement.classList.add('dark');
        },
        
        applyTheme() {
            document.documentElement.classList.add('dark');
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