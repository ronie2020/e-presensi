<x-student-learning-layout>
    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    {{-- CDN SweetAlert2 (Jika belum ada di layout utama) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Padding bottom besar di mobile agar konten tidak tertutup tombol submit --}}
    <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto min-h-screen pb-40 md:pb-32">
        
        {{-- QUIZ HEADER --}}
        <div class="animate-enter bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 rounded-[2.5rem] shadow-2xl shadow-blue-900/20 border border-white/10 p-8 md:p-10 mb-8 relative overflow-hidden text-white group">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-blue-500/20 transition-all duration-1000"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-purple-500/10 rounded-full blur-2xl -ml-10 -mb-10 group-hover:bg-purple-500/20 transition-all duration-1000"></div>
            
            <div class="relative z-10">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-[10px] font-black uppercase tracking-widest shadow-sm backdrop-blur-md">
                        <i class="ph-fill ph-monitor-play"></i> Kuis Online
                    </span>
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </div>
                
                <h1 class="text-2xl md:text-4xl font-black text-white mb-3 tracking-tight leading-tight">{{ $assignment->title }}</h1>
                <p class="text-slate-400 leading-relaxed max-w-2xl text-sm md:text-base font-medium">{{ $assignment->description }}</p>
                
                <div class="flex flex-wrap items-center gap-4 md:gap-6 mt-8 pt-6 border-t border-white/10">
                    <div class="flex items-center gap-2 text-sm font-bold text-slate-300 bg-white/5 px-4 py-2 rounded-xl border border-white/5">
                        <i class="ph-fill ph-clock text-yellow-400 text-lg"></i> 
                        <span>{{ $assignment->duration_minutes }} Menit</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm font-bold text-slate-300 bg-white/5 px-4 py-2 rounded-xl border border-white/5">
                        <i class="ph-fill ph-list-numbers text-purple-400 text-lg"></i> 
                        <span>{{ $assignment->questions->count() }} Soal</span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('students.learning.assignment.quiz.submit', $assignment->id) }}" method="POST" id="quizForm">
            @csrf
            
            <div class="space-y-6 md:space-y-8">
                @foreach($assignment->questions as $index => $q)
                    <div class="animate-enter bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden relative hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 group" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                        
                        {{-- Nomor Soal --}}
                        <div class="absolute top-0 left-0 bg-slate-100 text-slate-500 font-black text-xs px-4 py-2 rounded-br-2xl border-r border-b border-slate-200 z-10">
                            NO. {{ $index + 1 }}
                        </div>
                        
                        <div class="p-6 md:p-8 pt-12 md:pt-8">
                            {{-- Teks Soal --}}
                            <div class="text-lg md:text-xl font-bold text-slate-800 mb-8 leading-relaxed pl-2 border-l-4 border-blue-500 rounded-sm">
                                {!! nl2br(e($q->question_text)) !!}
                            </div>

                            <div class="space-y-3">
                                @if($q->question_type == 'multiple_choice')
                                    @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                                        @if(isset($q->options[$opt]) && $q->options[$opt])
                                            <label class="relative flex items-center gap-4 p-4 md:p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 group/opt select-none bg-slate-50/30 hover:bg-blue-50/30 border-slate-100 hover:border-blue-200 active:scale-[0.99]">
                                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}" class="peer sr-only">
                                                
                                                {{-- Indikator Huruf --}}
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm shrink-0 transition-all border-2
                                                    bg-white border-slate-200 text-slate-500 
                                                    peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white
                                                    group-hover/opt:border-blue-300 group-hover/opt:text-blue-600">
                                                    {{ $opt }}
                                                </div>
                                                
                                                {{-- Teks Jawaban --}}
                                                <span class="text-base font-medium text-slate-600 peer-checked:text-slate-900 peer-checked:font-bold transition-colors">
                                                    {{ $q->options[$opt] }}
                                                </span>
                                                
                                                {{-- Checkmark Icon --}}
                                                <div class="absolute right-4 text-blue-600 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100">
                                                    <i class="ph-fill ph-check-circle text-2xl"></i>
                                                </div>

                                                {{-- Active Ring (Optional Visual) --}}
                                                <div class="absolute inset-0 rounded-2xl ring-2 ring-blue-500 opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></div>
                                            </label>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="relative">
                                        <textarea name="answers[{{ $q->id }}]" rows="5" 
                                            class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-slate-700 placeholder:text-slate-400 p-4 font-medium transition-all resize-none shadow-inner" 
                                            placeholder="Ketik jawaban uraian Anda di sini..."></textarea>
                                        <div class="absolute bottom-3 right-3 text-slate-300 pointer-events-none"><i class="ph-bold ph-pencil-simple text-xl"></i></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Floating Submit Button --}}
            {{-- Posisi diatur: bottom-[80px] untuk mobile (supaya tidak tertutup navbar bawah) dan bottom-0 untuk desktop --}}
            <div class="fixed bottom-[80px] md:bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-lg border-t border-slate-200 flex justify-center z-[60] animate-enter" style="animation-delay: 800ms">
                <div class="w-full max-w-4xl flex justify-end">
                    
                    {{-- 
                        Ganti type="submit" menjadi type="button" 
                        dan tambahkan onclick="confirmSubmit()" 
                    --}}
                    <button type="button" onclick="confirmSubmit()"
                        class="w-full md:w-auto px-8 py-4 bg-blue-900 text-white font-bold rounded-2xl shadow-xl shadow-blue-900/20 hover:bg-slate-900 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 active:scale-95 group">
                        <span>Kumpulkan Jawaban</span>
                        <div class="bg-white/20 rounded-full p-1 group-hover:translate-x-1 transition-transform">
                            <i class="ph-bold ph-paper-plane-right text-lg"></i> 
                        </div>
                    </button>
                </div>
            </div>
        </form>

    </div>

    {{-- SCRIPT JAVASCRIPT KONFIRMASI --}}
    <script>
        function confirmSubmit() {
            Swal.fire({
                title: 'Sudah Yakin?',
                text: "Pastikan semua soal sudah terjawab. Jawaban tidak bisa diubah setelah dikumpulkan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e3a8a', // blue-900
                cancelButtonColor: '#64748b', // slate-500
                confirmButtonText: 'Ya, Kumpulkan!',
                cancelButtonText: 'Periksa Lagi',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans',
                    title: 'text-xl font-bold text-slate-800',
                    htmlContainer: 'text-slate-500',
                    confirmButton: 'px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-900/20',
                    cancelButton: 'px-6 py-3 rounded-xl font-bold hover:bg-slate-100 text-slate-600'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan Loading State
                    Swal.fire({
                        title: 'Mengirim Jawaban...',
                        html: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-[2rem] font-sans'
                        }
                    });

                    // Submit Form Secara Manual
                    document.getElementById('quizForm').submit();
                }
            });
        }
    </script>
</x-student-learning-layout>