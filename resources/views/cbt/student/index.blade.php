<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Ujian Tersedia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Siswa -->
            <div class="bg-blue-600 rounded-2xl p-6 mb-8 text-white shadow-lg shadow-blue-500/30 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold">Halo, {{ Auth::guard('student')->user()->name }}! 👋</h3>
                    <p class="text-blue-100 mt-1">Selamat datang di portal ujian. Silakan pilih ujian yang aktif dibawah ini.</p>
                </div>
                <div class="hidden md:block text-right">
                    <p class="text-xs font-bold uppercase opacity-70">NISN</p>
                    <p class="font-mono text-xl font-bold">{{ Auth::guard('student')->user()->student_id }}</p>
                </div>
            </div>

            <!-- Daftar Ujian -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-slate-100">
                @if($exams->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <i class="ph-duotone ph-files text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Tidak ada ujian aktif</h3>
                        <p class="text-slate-500">Saat ini belum ada jadwal ujian yang tersedia untuk Anda.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($exams as $exam)
                            <div class="group relative bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                                <!-- Hiasan Background -->
                                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                                
                                <div class="relative z-10">
                                    <div class="flex justify-between items-start mb-4">
                                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                            {{ $exam->subject_name }}
                                        </span>
                                        @if($exam->token)
                                            <span class="text-xs font-bold text-amber-600 flex items-center gap-1 bg-amber-50 px-2 py-1 rounded-lg border border-amber-100">
                                                <i class="ph-fill ph-lock-key"></i> Butuh Token
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-xl font-bold text-slate-800 mb-2 line-clamp-2 leading-tight min-h-[3.5rem]">{{ $exam->title }}</h3>
                                    
                                    <div class="space-y-3 text-sm text-slate-500 mb-6">
                                        <div class="flex items-center gap-2">
                                            <i class="ph-fill ph-clock text-blue-500"></i>
                                            <span class="font-medium">{{ $exam->duration_minutes }} Menit</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="ph-fill ph-calendar text-blue-500"></i>
                                            <span>{{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y, H:i') }}</span>
                                        </div>
                                    </div>

                                    <a href="{{ route('student.exam.showStart', $exam->id) }}" 
                                       class="block w-full text-center bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                                        Kerjakan Sekarang <i class="ph-bold ph-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>