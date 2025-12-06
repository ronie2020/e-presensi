{{-- Menggunakan Layout Khusus Siswa (resources/views/layouts/student.blade.php) --}}
<x-student-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <i class="ph-duotone ph-list-checks text-blue-600"></i>
            {{ __('Daftar Ujian Tersedia') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Info Siswa (Banner) -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 mb-8 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-blue-400 opacity-20 rounded-full blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h3 class="text-3xl font-black mb-2">Halo, {{ Auth::guard('student')->user()->name }}! 👋</h3>
                    <p class="text-blue-100 text-lg">Siap untuk mengerjakan ujian hari ini? Pilih ujian yang tersedia di bawah.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm px-6 py-3 rounded-2xl border border-white/20 text-center min-w-[150px]">
                    <p class="text-xs font-bold uppercase tracking-widest opacity-80 mb-1">NISN / ID</p>
                    <p class="font-mono text-2xl font-black tracking-wider">{{ Auth::guard('student')->user()->student_id }}</p>
                </div>
            </div>
        </div>

        <!-- Daftar Ujian -->
        <div class="space-y-6">
            @if($exams->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <i class="ph-duotone ph-files text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Tidak ada ujian aktif</h3>
                    <p class="text-slate-500 max-w-md mx-auto">Saat ini belum ada jadwal ujian yang tersedia. Silakan hubungi guru atau pengawas jika seharusnya ada ujian.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($exams as $exam)
                        <div class="group relative bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-300">
                            <!-- Header Card -->
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-blue-50 text-blue-700 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider border border-blue-100">
                                    {{ $exam->subject_name }}
                                </span>
                                @if($exam->token)
                                    <span class="text-[10px] font-bold text-amber-600 flex items-center gap-1 bg-amber-50 px-2 py-1 rounded-lg border border-amber-100" title="Memerlukan Token">
                                        <i class="ph-fill ph-lock-key"></i> Token
                                    </span>
                                @endif
                            </div>

                            <!-- Judul -->
                            <h3 class="text-xl font-bold text-slate-800 mb-3 line-clamp-2 min-h-[3.5rem] group-hover:text-blue-600 transition-colors">
                                {{ $exam->title }}
                            </h3>
                            
                            <!-- Info Waktu -->
                            <div class="space-y-2 text-sm text-slate-500 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-blue-500 shadow-sm">
                                        <i class="ph-fill ph-clock"></i>
                                    </div>
                                    <span class="font-bold">{{ $exam->duration_minutes }} Menit</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-blue-500 shadow-sm">
                                        <i class="ph-fill ph-calendar-check"></i>
                                    </div>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y, H:i') }}</span>
                                </div>
                            </div>

                            <!-- Tombol Action -->
                            <a href="{{ route('student.exam.showStart', $exam->id) }}" 
                               class="block w-full text-center bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:bg-blue-600 transition-all duration-300 shadow-lg shadow-slate-900/10 hover:shadow-blue-600/30 flex items-center justify-center gap-2 group-hover:scale-[1.02]">
                                Kerjakan Sekarang <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-student-layout>