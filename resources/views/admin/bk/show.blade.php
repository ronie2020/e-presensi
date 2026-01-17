<x-app-layout>
    {{-- 
        REDESIGN DETAIL KONSELING (BLUE THEME)
        - Menyesuaikan style dengan Index/Dashboard
        - Rounded corners besar, shadow halus, warna dominan biru/slate
    --}}
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        {{-- HEADER SECTION (Custom, menggantikan x-slot) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm font-bold text-blue-600 mb-1 uppercase tracking-wider">
                        <i class="ph-fill ph-hash"></i> Sesi Konseling {{ $session->id }}
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Proses & Tindak Lanjut</h1>
                    <p class="text-slate-500 font-medium">Kelola status pengajuan dan rekam hasil konseling siswa.</p>
                </div>
                <a href="{{ route('admin.bk.index') }}" class="group flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-slate-600 font-bold hover:border-blue-400 hover:text-blue-600 shadow-sm hover:shadow-md transition-all">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Notifikasi Flash Message -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl shadow-sm flex items-center gap-3" role="alert">
                    <div class="p-2 bg-emerald-200 rounded-full text-emerald-700">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                    </div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM KIRI: INFO SISWA & MASALAH -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- KARTU 1: Info Siswa -->
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/60 border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-6 opacity-5">
                            <i class="ph-duotone ph-student text-9xl text-blue-900"></i>
                        </div>
                        
                        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="ph-bold ph-user-circle"></i> Data Siswa
                        </h3>

                        <div class="flex flex-col items-center text-center mb-6 relative z-10">
                            <!-- Foto Profil -->
                            <div class="w-24 h-24 rounded-full p-1 bg-gradient-to-tr from-blue-500 to-purple-500 mb-4 shadow-lg shadow-blue-500/20">
                                <div class="w-full h-full rounded-full bg-white p-1 overflow-hidden">
                                    @if($session->student && $session->student->photo_path)
                                        <img class="w-full h-full rounded-full object-cover" src="{{ asset('storage/' . $session->student->photo_path) }}" alt="Foto Siswa">
                                    @else
                                        <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-3xl font-black">
                                            {{ substr($session->student->name ?? 'X', 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="font-black text-xl text-slate-800 leading-tight">{{ $session->student->name ?? 'Siswa Terhapus' }}</div>
                            <div class="text-sm font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full mt-2">
                                {{ $session->student->schoolClass->name ?? 'Tanpa Kelas' }}
                            </div>
                        </div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                                <span class="text-xs font-bold text-slate-400 uppercase">NIS / NISN</span>
                                <span class="font-bold text-slate-700 font-mono">{{ $session->student->nis ?? '-' }} / {{ $session->student->nisn ?? '-' }}</span>
                            </div>
                            
                            @if($session->student->parent_wa_number ?? false)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $session->student->parent_wa_number) }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-emerald-50 text-emerald-600 font-bold rounded-xl border border-emerald-100 hover:bg-emerald-100 transition-colors">
                                    <i class="ph-fill ph-whatsapp-logo text-xl"></i> 
                                    Hubungi Orang Tua
                                </a>
                            @else
                                <div class="flex items-center justify-center gap-2 w-full py-3 bg-slate-50 text-slate-400 font-bold rounded-xl border border-slate-100 cursor-not-allowed">
                                    <i class="ph-slash ph-whatsapp-logo text-xl"></i> No. WA Tidak Ada
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- KARTU 2: Detail Pengajuan -->
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/60 border border-slate-100">
                        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="ph-bold ph-chat-text"></i> Detail Pengajuan
                        </h3>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="px-3 py-1.5 text-xs rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100 font-bold uppercase tracking-wide">
                                <i class="ph-bold ph-tag mr-1"></i> {{ $session->category->name ?? 'Umum' }}
                            </span>
                            <span class="px-3 py-1.5 text-xs rounded-lg bg-slate-100 text-slate-600 border border-slate-200 font-bold uppercase tracking-wide">
                                @if($session->method == 'online')
                                    <i class="ph-bold ph-globe mr-1"></i> Online
                                @else
                                    <i class="ph-bold ph-users mr-1"></i> Tatap Muka
                                @endif
                            </span>
                        </div>
                        
                        <div class="relative mb-2">
                            <div class="absolute -top-3 -left-2 text-5xl text-blue-100 font-serif opacity-50">“</div>
                            <div class="relative z-10 bg-blue-50/50 p-5 rounded-2xl border border-blue-100 text-slate-700 italic font-medium leading-relaxed">
                                {{ $session->initial_message }}
                            </div>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-end gap-1.5 text-xs font-bold text-slate-400">
                            <i class="ph-bold ph-clock"></i> 
                            Diajukan: {{ $session->created_at->translatedFormat('d M Y, H:i') }}
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: AKSI & JURNAL -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- 1. FORM APPROVAL (Action Card) -->
                    @if($session->status == 'pending')
                    <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-amber-500/10 border border-amber-100 relative overflow-hidden" x-data="{ action: 'approved' }">
                        <!-- Accent Line -->
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                        
                        <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                            <div class="p-2 bg-amber-100 rounded-lg text-amber-600">
                                <i class="ph-fill ph-gavel text-xl"></i>
                            </div>
                            Tindakan Guru BK
                        </h3>

                        <form action="{{ route('admin.bk.update_status', $session->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keputusan</label>
                                    <div class="relative">
                                        <select name="status" x-model="action" class="w-full pl-4 pr-10 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all appearance-none cursor-pointer shadow-sm">
                                            <option value="approved">Setujui & Jadwalkan</option>
                                            <option value="rejected">Tolak Pengajuan</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                            <i class="ph-bold ph-caret-down"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Field Jadwal: Transisi Halus -->
                                <div x-show="action === 'approved'" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Jadwal Pertemuan</label>
                                    <input type="datetime-local" name="scheduled_at" 
                                           class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm" 
                                           :required="action === 'approved'">
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    <span x-text="action === 'approved' ? 'Pesan Konfirmasi (Lokasi/Catatan)' : 'Alasan Penolakan'"></span>
                                </label>
                                <textarea name="response_message" rows="3" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm" placeholder="Tulis pesan untuk siswa disini..." required></textarea>
                                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1.5 font-medium">
                                    <i class="ph-fill ph-info text-blue-400"></i> 
                                    Notifikasi WhatsApp akan dikirim otomatis ke Orang Tua/Siswa.
                                </p>
                            </div>

                            <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2">
                                <i class="ph-bold ph-paper-plane-right text-lg"></i> Simpan & Kirim Notifikasi
                            </button>
                        </form>
                    </div>
                    @endif

                    <!-- BLOK STATUS: REJECTED -->
                    @if($session->status == 'rejected')
                    <div class="bg-rose-50 rounded-[2rem] p-6 border border-rose-100 flex flex-col md:flex-row items-start gap-4">
                        <div class="p-3 bg-white rounded-2xl text-rose-500 shadow-sm shrink-0">
                            <i class="ph-duotone ph-x-circle text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-rose-800">Pengajuan Ditolak</h3>
                            <p class="text-rose-700/80 font-medium mt-1 text-sm leading-relaxed">
                                "{{ $session->response_message }}"
                            </p>
                            <div class="mt-3 text-xs font-bold text-rose-400 flex items-center gap-1">
                                <i class="ph-bold ph-clock"></i> Diproses pada: {{ $session->updated_at->translatedFormat('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- BLOK STATUS: JADWAL (Approved/Finished) -->
                    @if($session->status == 'approved' || $session->status == 'finished')
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/60 border border-slate-100 relative overflow-hidden">
                         <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                         
                        <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                            <div>
                                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                    <i class="ph-duotone ph-calendar-check text-blue-500 text-2xl"></i> Sesi Terjadwal
                                </h3>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                            <i class="ph-bold ph-clock"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-400 uppercase">Waktu Pertemuan</div>
                                            <div class="font-bold text-slate-800">{{ $session->scheduled_at ? $session->scheduled_at->translatedFormat('l, d F Y - H:i') : '-' }} WIB</div>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 pt-2">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                                            <i class="ph-bold ph-chat-centered-text"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-400 uppercase">Respon Guru</div>
                                            <div class="font-medium text-slate-600 text-sm italic">"{{ $session->response_message }}"</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($session->status == 'approved')
                                <div class="px-4 py-2 bg-blue-100 text-blue-700 rounded-xl font-bold text-xs flex items-center gap-2 animate-pulse">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Sedang Berlangsung
                                </div>
                            @else
                                <div class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl font-bold text-xs flex items-center gap-2">
                                    <i class="ph-fill ph-check-circle"></i> Selesai
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- 2. FORM JURNAL (Main Content) -->
                    @if($session->status == 'approved' || $session->status == 'finished')
                    <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/60 border border-slate-100">
                        <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-4">
                            <h3 class="text-xl font-black text-slate-800 flex items-center gap-3">
                                <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-600">
                                    <i class="ph-fill ph-notebook text-xl"></i>
                                </div>
                                Jurnal Konseling
                            </h3>
                            @if($session->status == 'finished')
                                <span class="text-xs bg-slate-100 text-slate-500 px-3 py-1.5 rounded-lg border border-slate-200 font-bold flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="ph-fill ph-lock-key"></i> Read Only
                                </span>
                            @endif
                        </div>

                        <!-- Form Input Jurnal -->
                        @if($session->status == 'approved')
                        <form action="{{ route('admin.bk.store_record', $session->id) }}" method="POST">
                            @csrf
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Analisis Masalah</label>
                                    <textarea name="problem_analysis" rows="4" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm" placeholder="Jelaskan akar permasalahan siswa secara detail..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Solusi / Tindakan</label>
                                    <textarea name="solution" rows="4" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm" placeholder="Nasihat, perlakuan, atau tindakan yang diberikan..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Hasil Akhir (Follow Up)</label>
                                    <textarea name="result" rows="2" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm" placeholder="Kesepakatan bersama atau rencana tindak lanjut..."></textarea>
                                </div>
                                
                                <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 cursor-pointer transition-colors group">
                                    <input type="checkbox" name="is_confidential" value="1" checked class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-colors">
                                    <div>
                                        <div class="text-sm font-bold text-slate-700 group-hover:text-blue-700 transition-colors">Bersifat Rahasia (Confidential)</div>
                                        <div class="text-xs text-slate-400">Hanya Guru BK & Kepala Sekolah yang dapat melihat catatan ini.</div>
                                    </div>
                                </label>

                                <div class="pt-4 border-t border-slate-100 mt-4">
                                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-4 rounded-xl font-bold text-sm shadow-lg shadow-emerald-500/30 flex justify-center items-center gap-2 transition-all transform hover:scale-[1.01]">
                                        <i class="ph-bold ph-check-circle text-xl"></i> Simpan & Selesaikan Sesi
                                    </button>
                                </div>
                            </div>
                        </form>
                        @else
                            <!-- TAMPILAN READ ONLY (Style Dokumen) -->
                            <div class="space-y-6">
                                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Analisis Masalah</h4>
                                    <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $session->record->problem_analysis ?? '-' }}</div>
                                </div>
                                
                                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Solusi / Tindakan</h4>
                                    <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $session->record->solution ?? '-' }}</div>
                                </div>

                                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Hasil Akhir</h4>
                                    <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $session->record->result ?? '-' }}</div>
                                </div>

                                @if($session->record && $session->record->is_confidential)
                                    <div class="flex items-center gap-2 px-4 py-3 bg-rose-50 text-rose-700 rounded-xl font-bold text-xs border border-rose-100 w-fit">
                                        <i class="ph-fill ph-lock-key"></i> Dokumen Rahasia (Confidential)
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>