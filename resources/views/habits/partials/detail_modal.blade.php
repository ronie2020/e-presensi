<div class="space-y-8">
    {{-- Header Modal --}}
    <div class="flex items-center gap-5 border-b border-slate-100 pb-6">
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center overflow-hidden border-2 border-emerald-100 shadow-inner">
            @if($habit->student->photo_path)
                <img src="{{ asset('storage/' . $habit->student->photo_path) }}" class="w-full h-full object-cover">
            @else
                <i class="ph-fill ph-user text-3xl text-emerald-300"></i>
            @endif
        </div>
        <div>
            <h3 class="font-black text-2xl text-slate-800 tracking-tight">{{ $habit->student->name }}</h3>
            <div class="flex items-center gap-3 mt-1">
                <span class="px-2.5 py-0.5 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase">Kelas {{ $habit->student->schoolClass->name ?? '-' }}</span>
                <span class="text-slate-400 text-xs font-medium"><i class="ph-bold ph-calendar-blank mr-1"></i>{{ $habit->report_date->translatedFormat('d F Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Bukti Foto --}}
    @if($habit->photo_path)
    <div class="space-y-3">
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
            <i class="ph-bold ph-image text-sm text-emerald-500"></i> Bukti Dokumentasi
        </label>
        <div class="rounded-3xl overflow-hidden border-4 border-slate-50 shadow-lg bg-slate-100">
            <img src="{{ asset('storage/' . $habit->photo_path) }}" 
                class="w-full max-h-[400px] object-contain mx-auto transition-transform hover:scale-[1.05] duration-500 cursor-zoom-in"
                alt="Bukti Kebiasaan Siswa">
        </div>
    </div>
    @endif

    {{-- Grid 7 Kebiasaan (LOGIKA URUTAN BARU) --}}
    <div class="space-y-4">
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
            <i class="ph-bold ph-list-checks text-sm text-emerald-500"></i> Laporan 7 Kebiasaan
        </label>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            
            {{-- 1. BANGUN & MANDI --}}
            <div class="flex flex-col p-4 rounded-2xl border {{ ($habit->habit_1 && $habit->habit_2) ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-100 opacity-60' }}">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-sun-horizon {{ $habit->habit_1 ? 'text-emerald-600' : 'text-slate-400' }} text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">1. Bangun & Mandi</span>
                    </div>
                    <i class="ph-fill {{ ($habit->habit_1 && $habit->habit_2) ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300' }}"></i>
                </div>
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">
                    Bangun jam {{ $habit->habit_1_time ?? '-' }}
                </p>
            </div>

            {{-- 2. SHALAT (DETAIL CHECKLIST) & ODOA --}}
            <div class="flex flex-col p-4 rounded-2xl border bg-white border-slate-200 md:row-span-2">
                
                {{-- LIST SHALAT --}}
                <div class="flex items-center gap-3 mb-3 border-b border-slate-100 pb-2">
                    <i class="ph-bold ph-mosque text-emerald-600 text-lg"></i>
                    <span class="text-sm font-bold text-slate-700">2. Ibadah Harian</span>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-4">
                    @php
                        $prayers = [
                            ['Subuh', $habit->prayer_subuh],
                            ['Dhuha', $habit->prayer_dhuha],
                            ['Dzuhur', $habit->prayer_dzuhur],
                            ['Ashar', $habit->prayer_ashar],
                            ['Maghrib', $habit->prayer_maghrib],
                            ['Isya', $habit->prayer_isya],
                        ];
                    @endphp
                    @foreach($prayers as $p)
                        <div class="flex items-center gap-2">
                            <i class="ph-fill {{ $p[1] ? 'ph-check-circle text-emerald-500' : 'ph-circle text-slate-300' }} text-sm"></i>
                            <span class="text-[10px] font-bold {{ $p[1] ? 'text-slate-700' : 'text-slate-400' }}">{{ $p[0] }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- AUDIO ODOA --}}
                @if($habit->odoa_audio_path || $habit->odoa_surah)
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">One Day One Ayat</p>
                        <p class="text-xs font-bold text-slate-700 mb-2">
                            {{ $habit->odoa_surah ?? '-' }} : Ayat {{ $habit->odoa_ayat ?? '-' }}
                        </p>
                        @if($habit->odoa_audio_path)
                            <audio controls class="w-full h-6">
                                <source src="{{ asset('storage/'.$habit->odoa_audio_path) }}" type="audio/mpeg">
                                Browser tidak support audio.
                            </audio>
                        @else
                            <p class="text-[9px] text-rose-400 italic">Tidak ada rekaman suara.</p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- 3. OLAHRAGA --}}
            <div class="flex flex-col p-4 rounded-2xl border {{ $habit->habit_3 ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-100 opacity-60' }}">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-sneaker-move {{ $habit->habit_3 ? 'text-emerald-600' : 'text-slate-400' }} text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">3. Berolahraga</span>
                    </div>
                    <i class="ph-fill {{ $habit->habit_3 ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300' }}"></i>
                </div>
                @if($habit->habit_3_activity)
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">"{{ $habit->habit_3_activity }}"</p>
                @endif
            </div>

            {{-- 4. MAKAN BERGIZI --}}
            <div class="flex flex-col p-4 rounded-2xl border {{ $habit->habit_5 ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-100 opacity-60' }}">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-carrot {{ $habit->habit_5 ? 'text-emerald-600' : 'text-slate-400' }} text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">4. Makan Bergizi</span>
                    </div>
                    <i class="ph-fill {{ $habit->habit_5 ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300' }}"></i>
                </div>
                @if($habit->habit_5_menu)
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">Menu: {{ $habit->habit_5_menu }}</p>
                @endif
            </div>

            {{-- 5. BELAJAR --}}
            <div class="flex flex-col p-4 rounded-2xl border {{ $habit->habit_4 ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-100 opacity-60' }}">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-book-open-text {{ $habit->habit_4 ? 'text-emerald-600' : 'text-slate-400' }} text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">5. Gemar Belajar</span>
                    </div>
                    <i class="ph-fill {{ $habit->habit_4 ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300' }}"></i>
                </div>
                @if($habit->habit_4_subject)
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">Mapel: {{ $habit->habit_4_subject }}</p>
                @endif
            </div>

            {{-- 6. SOSIAL --}}
            <div class="flex flex-col p-4 rounded-2xl border {{ $habit->habit_6 ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50 border-slate-100 opacity-60' }}">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-3">
                        <i class="ph-bold ph-users-three {{ $habit->habit_6 ? 'text-emerald-600' : 'text-slate-400' }} text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">6. Bantu Orang Tua</span>
                    </div>
                    <i class="ph-fill {{ $habit->habit_6 ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300' }}"></i>
                </div>
                @if($habit->habit_6_activity)
                <p class="text-[10px] text-slate-500 ml-8 font-medium italic">"{{ $habit->habit_6_activity }}"</p>
                @endif
            </div>

            {{-- 7. TIDUR --}}
            <div class="md:col-span-2 flex items-center justify-between p-4 rounded-2xl border {{ $habit->habit_7 ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-100 opacity-60' }}">
                <div class="flex items-center gap-3">
                    <i class="ph-bold ph-moon {{ $habit->habit_7 ? 'text-emerald-600' : 'text-slate-400' }} text-lg"></i>
                    <div>
                        <span class="text-sm font-bold text-slate-700">7. Tidur Cepat</span>
                        @if($habit->habit_7_time)
                            <p class="text-[10px] font-bold text-emerald-600">Jam: {{ $habit->habit_7_time }}</p>
                        @endif
                    </div>
                </div>
                <i class="ph-fill {{ $habit->habit_7 ? 'ph-check-circle text-emerald-600' : 'ph-x-circle text-slate-300' }}"></i>
            </div>
        </div>
    </div>

    {{-- Feedback Form (TETAP SAMA) --}}
    <div class="pt-4 border-t border-slate-100">
        <form action="{{ route('teacher.habits.feedback', $habit->id) }}" method="POST" class="bg-slate-900 p-6 rounded-[2rem] shadow-xl relative overflow-hidden">
            @csrf
            <div class="absolute top-0 right-0 p-4 opacity-10"><i class="ph-fill ph-chat-centered-text text-6xl text-white"></i></div>
            <label class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em] mb-3 block">Berikan Apresiasi / Catatan</label>
            <textarea name="feedback" rows="3" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white text-sm focus:ring-emerald-500 focus:border-emerald-500 placeholder-slate-500" placeholder="Tulis pesan motivasi...">{{ $habit->teacher_feedback }}</textarea>
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-black text-xs uppercase tracking-widest px-8 py-3 rounded-xl transition-all shadow-lg">Kirim Feedback</button>
            </div>
        </form>
    </div>
</div>