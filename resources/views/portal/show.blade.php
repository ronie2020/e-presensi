@extends('layouts.public')

@section('content')
<div class="w-full max-w-5xl mx-auto">
    
    <!-- 1. Header Profil & Foto -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8 border border-gray-100 relative group">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-32 w-full absolute top-0 left-0 z-0">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        </div>
        
        <div class="relative z-10 px-8 pt-16 pb-8 flex flex-col md:flex-row items-center md:items-end text-center md:text-left">
            
            <!-- UPDATE: Foto Siswa -->
            <div class="bg-white p-1.5 rounded-full shadow-2xl mb-4 md:mb-0 relative -mt-12 md:-mt-0">
                <div class="w-32 h-32 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-4 border-white relative shadow-inner">
                    @if($student->photo_path)
                        {{-- Menampilkan foto dari storage --}}
                        <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                    @else
                        {{-- Placeholder Inisial jika tidak ada foto --}}
                        <div class="w-full h-full bg-blue-50 flex items-center justify-center text-4xl font-black text-blue-300 select-none">
                            {{ substr($student->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="md:ml-6 mb-2 flex-1 pt-2 md:pt-0">
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight mb-2">{{ $student->name }}</h1>
                <div class="flex flex-col md:flex-row gap-2 md:gap-4 text-gray-600 text-sm font-medium justify-center md:justify-start">
                    <span class="flex items-center bg-blue-50 px-3 py-1.5 rounded-full text-blue-700 border border-blue-100">
                        <i class="ph-fill ph-chalkboard-teacher mr-2 text-lg"></i>
                        Kelas {{ $student->schoolClass->name ?? 'Belum Diatur' }}
                    </span>
                    <span class="flex items-center bg-gray-100 px-3 py-1.5 rounded-full text-gray-700 border border-gray-200 font-mono">
                        <i class="ph-fill ph-identification-card mr-2 text-lg text-gray-500"></i>
                        {{ $student->student_id }}
                    </span>
                </div>
            </div>

            <div class="md:ml-auto mt-6 md:mt-0">
                <a href="{{ route('portal.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm group">
                    <i class="ph-bold ph-magnifying-glass mr-2 group-hover:scale-110 transition-transform"></i>
                    Cari Siswa Lain
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Statistik Kehadiran -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4 px-2">
            <div class="p-2 bg-blue-600 rounded-lg text-white shadow-lg shadow-blue-200">
                <i class="ph-bold ph-chart-bar text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Rekap Kehadiran</h3>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-100 flex flex-col items-center justify-center text-center hover:shadow-md transition">
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 mb-2"><i class="ph-duotone ph-check-circle text-3xl"></i></div>
                <p class="text-4xl font-black text-emerald-600 mb-1">{{ $hadir }}</p>
                <p class="text-xs text-gray-400 uppercase font-bold tracking-widest">Hadir</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-blue-100 flex flex-col items-center justify-center text-center hover:shadow-md transition">
                <div class="p-3 bg-blue-50 rounded-xl text-blue-600 mb-2"><i class="ph-duotone ph-thermometer text-3xl"></i></div>
                <p class="text-4xl font-black text-blue-600 mb-1">{{ $sakit }}</p>
                <p class="text-xs text-gray-400 uppercase font-bold tracking-widest">Sakit</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-amber-100 flex flex-col items-center justify-center text-center hover:shadow-md transition">
                <div class="p-3 bg-amber-50 rounded-xl text-amber-500 mb-2"><i class="ph-duotone ph-hand-waving text-3xl"></i></div>
                <p class="text-3xl font-black text-amber-500 mb-1">{{ $izin }}</p>
                <p class="text-xs text-gray-400 uppercase font-bold tracking-widest">Izin</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-rose-100 flex flex-col items-center justify-center text-center hover:shadow-md transition">
                <div class="p-3 bg-rose-50 rounded-xl text-rose-600 mb-2"><i class="ph-duotone ph-x-circle text-3xl"></i></div>
                <p class="text-4xl font-black text-rose-600 mb-1">{{ $alpa }}</p>
                <p class="text-xs text-gray-400 uppercase font-bold tracking-widest">Alpa</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- 3. Rekap Disiplin -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 px-2">
                <div class="p-2 bg-indigo-600 rounded-lg text-white shadow-lg shadow-indigo-200">
                    <i class="ph-bold ph-star text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Catatan Karakter</h3>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
                 <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-rose-50 rounded-2xl text-center border border-rose-100">
                        <p class="text-xs font-bold text-rose-500 uppercase tracking-widest mb-1">Pelanggaran</p>
                        <p class="text-4xl font-black text-rose-600">{{ $poin_pelanggaran ?? 0 }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">Poin</p>
                    </div>
                    <div class="p-4 bg-emerald-50 rounded-2xl text-center border border-emerald-100">
                        <p class="text-xs font-bold text-emerald-500 uppercase tracking-widest mb-1">Kebaikan</p>
                        <p class="text-4xl font-black text-emerald-600">{{ $poin_kebaikan ?? 0 }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">Poin</p>
                    </div>
                 </div>
                 <div class="mt-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Aktivitas Terakhir</h4>
                    <div class="space-y-3">
                        @forelse ($discipline_history as $record)
                            <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="mt-0.5">
                                    @if($record->disciplineType->type == 'Kebaikan')
                                        <i class="ph-fill ph-thumbs-up text-emerald-500"></i>
                                    @else
                                        <i class="ph-fill ph-warning text-rose-500"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $record->disciplineType->name }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($record->date)->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="ml-auto text-xs font-black {{ $record->disciplineType->type == 'Kebaikan' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $record->disciplineType->type == 'Kebaikan' ? '+' : '-' }}{{ $record->disciplineType->point_value }}
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-sm text-gray-400 py-4">Belum ada catatan.</p>
                        @endforelse
                    </div>
                 </div>
            </div>
        </div>

        <!-- 4. Rekap Perpustakaan (BARU) -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 px-2">
                <div class="p-2 bg-orange-500 rounded-lg text-white shadow-lg shadow-orange-200">
                    <i class="ph-bold ph-books text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Aktivitas Perpustakaan</h3>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden h-full">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-3xl font-black text-gray-800">{{ $library_visits }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Kali Berkunjung</p>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-2xl text-orange-500">
                        <i class="ph-duotone ph-read-cv-logo text-4xl"></i>
                    </div>
                </div>

                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Buku Dipinjam Terakhir</h4>
                <div class="space-y-3 overflow-y-auto max-h-64 pr-1 custom-scrollbar">
                    @forelse($borrowing_history as $loan)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="flex-shrink-0 w-8 h-10 bg-gray-200 rounded overflow-hidden">
                                     @if($loan->book->cover_path)
                                        <img src="{{ asset('storage/' . $loan->book->cover_path) }}" class="w-full h-full object-cover">
                                     @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="ph-fill ph-book"></i></div>
                                     @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $loan->book->title }}</p>
                                    <p class="text-[10px] text-gray-500">Pinjam: {{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $loan->status == 'returned' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600' }}">
                                {{ $loan->status == 'returned' ? 'Kembali' : 'Dipinjam' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <i class="ph-duotone ph-book-open text-3xl mb-2"></i>
                            <p class="text-sm">Belum pernah meminjam buku.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection