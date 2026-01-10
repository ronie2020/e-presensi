@extends('layouts.public')

@section('content')
{{-- SET LOCALE --}}
@php
    \Carbon\Carbon::setLocale('id');
    $isAlumni = $student->status === 'graduated';
@endphp

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 0px; background: transparent; }
    .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
    .ph-fill, .ph-duotone, .ph-bold { vertical-align: middle; }
</style>

<!-- X-DATA: Main Controller -->
<div class="w-full max-w-6xl mx-auto pb-20 px-4 sm:px-6"
     x-data="{ 
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'ringkasan',
        updateTab(val) {
            this.activeTab = val;
            const url = new URL(window.location);
            url.searchParams.set('tab', val);
            window.history.pushState({}, '', url);
            
            // Trigger resize untuk Chart.js saat tab berubah
            if(val === 'akademik' || val === 'kehadiran') {
                setTimeout(() => { 
                    window.dispatchEvent(new Event('resize')); 
                }, 100);
            }
        }
     }">
    
    {{-- 1. HEADER PROFIL --}}
    @include('students.portal.partials.header')

    {{-- 2. NAVIGATION TABS --}}
    @include('students.portal.partials.tabs-nav')

    {{-- 3. CONTENT AREAS --}}
    <div class="min-h-[400px]">
        
        <!-- Tab Ringkasan -->
        <div x-show="activeTab === 'ringkasan'" x-transition:enter="transition ease-out duration-300">
            @include('students.portal.partials.tab-ringkasan')
        </div>

        @if(!$isAlumni)
            <!-- Tab 7 Kebiasaan -->
            <div x-show="activeTab === 'kebiasaan'" x-cloak x-transition:enter="transition ease-out duration-300">
                @include('students.portal.partials.tab-kebiasaan')
            </div>

            <!-- Tab Buku Penghubung -->
            <div x-show="activeTab === 'penghubung'" x-cloak x-transition:enter="transition ease-out duration-300">
                @include('students.portal.partials.tab-penghubung')
            </div>

            <!-- Tab Pengaduan -->
            <div x-show="activeTab === 'pengaduan'" x-cloak x-transition:enter="transition ease-out duration-300">
                @include('students.portal.partials.tab-pengaduan')
            </div>

            <!-- Tab Jadwal -->
            <div x-show="activeTab === 'jadwal'" x-cloak x-transition:enter="transition ease-out duration-300">
                @include('students.portal.partials.tab-jadwal')
            </div>

            <!-- Tab LMS (Tugas) -->
            <div x-show="activeTab === 'lms'" x-cloak x-transition:enter="transition ease-out duration-300">
                @include('students.portal.partials.tab-lms')
            </div>

            <!-- Tab Jurnal KBM -->
            <div x-show="activeTab === 'kbm'" x-cloak x-transition:enter="transition ease-out duration-300">
                @include('students.portal.partials.tab-kbm')
            </div>

            <!-- Tab Akademik (Nilai) -->
            <div x-show="activeTab === 'akademik'" x-cloak x-transition:enter="transition ease-out duration-300">
                @include('students.portal.partials.tab-akademik')
            </div>

            <!-- Tab Kehadiran -->
            <div x-show="activeTab === 'kehadiran'" x-cloak x-transition:enter="transition ease-out duration-300">
                @include('students.portal.partials.tab-kehadiran')
            </div>

            <!-- Tab Disiplin -->
            <div x-show="activeTab === 'disiplin'" x-cloak x-transition:enter="transition ease-out duration-300">
                @include('students.portal.partials.tab-disiplin')
            </div>

            <!-- Tab Keagamaan (New/Separate) -->
            <div x-show="activeTab === 'keagamaan'" x-cloak x-transition:enter="transition ease-out duration-300">
                @include('students.portal.partials.tab-keagamaan')
            </div>
        @endif
        
        <!-- Tab Prestasi (Alumni & Siswa) -->
        <div x-show="activeTab === 'prestasi'" x-cloak x-transition:enter="transition ease-out duration-300">
            @include('students.portal.partials.tab-prestasi')
        </div>

        <!-- Tab Perpustakaan (Alumni & Siswa) -->
        <div x-show="activeTab === 'perpustakaan'" x-cloak x-transition:enter="transition ease-out duration-300">
            @include('students.portal.partials.tab-perpustakaan')
        </div>

    </div>
</div>

{{-- 4. SCRIPTS --}}
@include('students.portal.partials.scripts')

@endsection