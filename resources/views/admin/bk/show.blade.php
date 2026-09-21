<x-app-layout>
    <style>
        /* CSS Khusus untuk Mode Cetak (Print / Save as PDF) */
        @media print {
            body { background-color: white !important; color: black !important; }
            .print\:hidden { display: none !important; }
            .print\:block { display: block !important; }
            .shadow-xl, .shadow-sm, .shadow-md, .shadow-lg, .shadow-2xl { box-shadow: none !important; }
            .border { border: 1px solid #cbd5e1 !important; }
            .bg-white, .bg-slate-50, .bg-[#020b18], .bg-[#021124] { background-color: white !important; color: black !important; }
            .break-inside-avoid { break-inside: avoid; } 
            @page { margin: 1.5cm; }
        }
        
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
        [x-cloak] { display: none !important; }
        
        /* Style untuk input yang dikunci (Jurnal Selesai) */
        .input-locked {
            background-color: rgba(2, 17, 36, 0.6) !important;
            color: #94a3b8 !important;
            cursor: not-allowed;
            border-style: dashed !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        /* Styling untuk File Dropzone */
        .file-drop-area {
            border: 2px dashed rgba(255, 255, 255, 0.2); transition: all 0.3s ease;
        }
        .file-drop-area:hover, .file-drop-area.dragover {
            border-color: #56bbf1; background-color: rgba(86, 187, 241, 0.05);
        }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen pb-32" x-data="bkTeacherChatHandler({{ $session->id }})">
        
        {{-- ========================================================= --}}
        {{-- KOP SURAT (HANYA MUNCUL SAAT DI-PRINT / CETAK PDF)        --}}
        {{-- ========================================================= --}}
        <div class="hidden print:block w-full border-b-4 border-double border-slate-800 pb-4 mb-8 text-center text-black">
            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-600 mb-1">Pemerintah Provinsi Daerah</h3>
            <h1 class="text-2xl font-black uppercase tracking-wider text-slate-900 mb-1">Nama Sekolah Anda</h1>
            <p class="text-xs font-medium text-slate-700">Jl. Contoh Alamat Sekolah No. 123, Kota/Kabupaten, Kode Pos 12345</p>
            <p class="text-xs font-medium text-slate-700">Telp: (0123) 456789 | Email: info@sekolahanda.sch.id | Web: sekolahanda.sch.id</p>
            <h2 class="text-lg font-bold uppercase tracking-widest text-slate-800 mt-6 underline decoration-2 underline-offset-4">Dokumen Jurnal Bimbingan Konseling</h2>
            <p class="text-xs font-bold text-slate-500 mt-2">No. Referensi: BK-{{ date('Y') }}-{{ str_pad($session->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>

        {{-- HEADER SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 print:hidden">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-[10px] font-black text-[#56bbf1] mb-1 uppercase tracking-widest bg-[#56bbf1]/10 px-3 py-1 rounded-full border border-[#56bbf1]/20 w-fit">
                        <i class="ph-fill ph-hash"></i> Sesi Konseling {{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}
                    </div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Proses & Tindak Lanjut</h1>
                    <p class="text-slate-400 font-medium text-sm mt-1">Kelola status pengajuan dan rekam hasil konseling siswa.</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    {{-- TOMBOL CETAK (Hanya Muncul Jika Selesai) --}}
                    @if($session->status == 'finished')
                        <button onclick="window.print()" class="group flex items-center gap-2 px-5 py-2.5 bg-[#56bbf1]/10 border border-[#56bbf1]/20 rounded-xl text-[#56bbf1] font-bold hover:bg-[#56bbf1] hover:text-[#020b18] shadow-sm transition-all active:scale-95 text-sm">
                            <i class="ph-bold ph-printer group-hover:animate-bounce"></i>
                            Cetak Jurnal
                        </button>
                    @endif

                    <a href="{{ route('admin.bk.index') }}" class="group flex items-center gap-2 px-5 py-2.5 bg-white/10 border border-white/15 rounded-xl text-slate-200 font-bold hover:bg-white/20 hover:text-white shadow-sm transition-all active:scale-95 text-sm">
                        <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- VISUAL STATUS TRACKER (TIMELINE) --}}
            <div class="mt-8 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 rounded-[1.5rem] border border-white/10 shadow-2xl flex items-center justify-between relative overflow-hidden">
                <div class="absolute top-1/2 left-10 right-10 h-1.5 bg-white/10 -translate-y-1/2 rounded-full z-0"></div>
                
                @php
                    $isApproved = in_array($session->status, ['approved', 'finished', 'ongoing']);
                    $isFinished = $session->status == 'finished';
                    $isRejected = $session->status == 'rejected';
                @endphp

                <!-- Step 1: Pengajuan -->
                <div class="relative z-10 flex flex-col items-center bg-[#021124] px-4 rounded-xl">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-[#020b18] shadow-md bg-[#56bbf1] ring-4 ring-[#021124]">
                        <i class="ph-bold ph-check"></i>
                    </div>
                    <div class="text-[10px] font-black text-white uppercase tracking-wider mt-2">Pengajuan</div>
                </div>

                <!-- Step 2: Respon -->
                @if($isRejected)
                    <div class="relative z-10 flex flex-col items-center bg-[#021124] px-4 rounded-xl">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white shadow-md bg-rose-500 ring-4 ring-[#021124]">
                            <i class="ph-bold ph-x"></i>
                        </div>
                        <div class="text-[10px] font-black text-rose-400 uppercase tracking-wider mt-2">Ditolak</div>
                    </div>
                @else
                    <div class="relative z-10 flex flex-col items-center bg-[#021124] px-4 rounded-xl">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold ring-4 ring-[#021124] transition-colors duration-500 {{ $isApproved ? 'bg-[#56bbf1] text-[#020b18]' : 'bg-white/10 text-slate-400' }}">
                            @if($isApproved) <i class="ph-bold ph-check"></i> @else <span class="text-sm">2</span> @endif
                        </div>
                        <div class="text-[10px] font-black uppercase tracking-wider mt-2 transition-colors duration-500 {{ $isApproved ? 'text-white' : 'text-slate-400' }}">Tanggapan BK</div>
                    </div>
                @endif

                <!-- Step 3: Selesai -->
                @if(!$isRejected)
                    <div class="relative z-10 flex flex-col items-center bg-[#021124] px-4 rounded-xl">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold ring-4 ring-[#021124] transition-colors duration-500 {{ $isFinished ? 'bg-emerald-500 text-white' : 'bg-white/10 text-slate-400' }}">
                            @if($isFinished) <i class="ph-bold ph-check-circle text-lg"></i> @else <span class="text-sm">3</span> @endif
                        </div>
                        <div class="text-[10px] font-black uppercase tracking-wider mt-2 transition-colors duration-500 {{ $isFinished ? 'text-emerald-400' : 'text-slate-400' }}">Selesai / Arsip</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- ========================================== -->
                <!-- KOLOM KIRI: INFO SISWA & MASALAH           -->
                <!-- ========================================== -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- KARTU 1: Info Siswa -->
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-8 shadow-2xl border border-white/10 relative overflow-hidden break-inside-avoid">
                        <div class="absolute top-0 right-0 p-6 opacity-5 print:hidden">
                            <i class="ph-duotone ph-student text-9xl text-[#56bbf1]"></i>
                        </div>
                        
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="ph-bold ph-user-circle text-base text-[#56bbf1]"></i> Data Siswa
                        </h3>

                        <div class="flex flex-col items-center text-center mb-6 relative z-10">
                            <!-- Foto Profil -->
                            <div class="w-24 h-24 rounded-[1.5rem] p-1 bg-gradient-to-tr from-[#56bbf1] to-blue-600 mb-4 shadow-lg shadow-[#56bbf1]/20 print:hidden overflow-hidden">
                                <div class="w-full h-full rounded-2xl bg-[#021124] p-1 overflow-hidden">
                                    @if($session->student && $session->student->photo_path)
                                        <img class="w-full h-full rounded-xl object-cover" src="{{ asset('storage/' . $session->student->photo_path) }}" alt="Foto Siswa">
                                    @else
                                        <div class="w-full h-full rounded-xl bg-white/10 flex items-center justify-center text-[#56bbf1] text-3xl font-black">
                                            {{ substr($session->student->name ?? 'X', 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="font-black text-xl text-white leading-tight">{{ $session->student->name ?? 'Siswa Terhapus' }}</div>
                            <div class="text-[10px] font-black text-[#56bbf1] bg-[#56bbf1]/10 px-3 py-1 rounded-full mt-2 border border-[#56bbf1]/20 uppercase tracking-wider mb-2">
                                {{ $session->student->schoolClass->name ?? 'Tanpa Kelas' }}
                            </div>

                            @if($session->groupMembers && $session->groupMembers->count() > 0)
                                <div class="mt-4 pt-4 border-t border-white/10 w-full text-left">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 text-center">Anggota Kelompok</span>
                                    <div class="flex flex-col gap-2">
                                        @foreach($session->groupMembers as $member)
                                            <div class="flex items-center gap-2 bg-[#021124]/90 p-2 rounded-xl border border-white/10">
                                                <div class="w-6 h-6 rounded-lg bg-[#56bbf1] text-[#020b18] flex items-center justify-center text-[10px] font-bold">
                                                    {{ substr($member->name, 0, 1) }}
                                                </div>
                                                <div class="text-xs font-bold text-slate-300 truncate flex-1">{{ $member->name }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex justify-between items-center p-4 bg-white/5 border border-white/10 rounded-2xl">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">NIS / NISN</span>
                                <span class="text-xs font-bold text-white font-mono bg-[#021124] px-2 py-1 rounded-md border border-white/15 shadow-sm">{{ $session->student->nis ?? '-' }} / {{ $session->student->student_id ?? '-' }}</span>
                            </div>
                            
                            <div class="print:hidden space-y-2">
                                @if($session->student->parent_wa_number ?? false)
                                    @php
                                        $waMessage = "Salam hormat Bapak/Ibu Orang Tua/Wali dari " . ($session->student->name ?? '') . ",\n\nKami dari pihak Bimbingan Konseling sekolah ingin berdiskusi terkait ananda. Mohon konfirmasi ketersediaan Bapak/Ibu untuk komunikasi lebih lanjut. Terima kasih.";
                                        $waLink = "https://wa.me/" . preg_replace('/^0/', '62', $session->student->parent_wa_number) . "?text=" . urlencode($waMessage);
                                    @endphp
                                    <a href="{{ $waLink }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3.5 bg-emerald-500/10 text-emerald-400 font-bold text-sm rounded-xl border border-emerald-500/20 hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                                        <i class="ph-fill ph-whatsapp-logo text-xl"></i> 
                                        Hubungi Orang Tua
                                    </a>
                                @else
                                    <div class="flex items-center justify-center gap-2 w-full py-3.5 bg-white/5 text-slate-500 font-bold text-sm rounded-xl border border-white/10 cursor-not-allowed">
                                        <i class="ph-slash ph-whatsapp-logo text-xl"></i> No. WA Tidak Ada
                                    </div>
                                @endif

                                @if(Route::has('admin.discipline.student_history'))
                                    <a href="{{ route('admin.discipline.student_history', $session->student->id) }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3.5 bg-white/10 text-slate-200 font-bold text-sm rounded-xl border border-white/15 hover:bg-white/20 hover:text-white transition-all shadow-sm">
                                        <i class="ph-bold ph-shield-warning text-xl text-amber-400"></i> 
                                        Rekam Kedisiplinan
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- KARTU 2: Detail Pengajuan -->
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-8 shadow-2xl border border-white/10 break-inside-avoid relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#56bbf1] to-blue-600"></div>

                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 mt-2 flex items-center gap-2">
                            <i class="ph-bold ph-chat-text text-base text-[#56bbf1]"></i> Detail Pengajuan
                        </h3>
                        
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="px-3 py-1.5 text-[10px] rounded-lg bg-[#56bbf1]/10 text-[#56bbf1] border border-[#56bbf1]/20 font-black uppercase tracking-widest shadow-sm">
                                <i class="ph-bold ph-tag mr-1"></i> {{ $session->category->name ?? 'Umum' }}
                            </span>
                            <span class="px-3 py-1.5 text-[10px] rounded-lg bg-white/10 text-slate-300 border border-white/15 font-black uppercase tracking-widest shadow-sm">
                                @if($session->method == 'online')
                                    <i class="ph-bold ph-globe mr-1"></i> Online
                                @else
                                    <i class="ph-bold ph-users mr-1"></i> Tatap Muka
                                @endif
                            </span>
                        </div>

                        {{-- ALERT INTEGRASI SISTEM DISIPLIN --}}
                        @if($session->is_system_generated)
                            @if(str_contains($session->initial_message, 'PELANGGARAN'))
                                <div class="mb-6 bg-rose-500/20 border border-rose-500/30 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
                                    <div class="p-2 bg-rose-500/20 text-rose-300 rounded-xl shadow-sm animate-pulse print:hidden shrink-0 border border-rose-500/30">
                                        <i class="ph-fill ph-warning-octagon text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-[10px] font-black text-rose-300 uppercase tracking-widest mb-1">Panggilan Otomatis Sistem</h4>
                                        <p class="text-xs text-rose-200 font-medium leading-relaxed">Tiket ini dibuat otomatis karena siswa mencapai ambang batas poin pelanggaran di modul Disiplin.</p>
                                    </div>
                                </div>
                            @elseif(str_contains($session->initial_message, 'PRESTASI'))
                                <div class="mb-6 bg-[#56bbf1]/20 border border-[#56bbf1]/30 rounded-2xl p-4 flex items-start gap-3 shadow-sm">
                                    <div class="p-2 bg-[#56bbf1]/20 text-[#56bbf1] rounded-xl shadow-sm print:hidden shrink-0 border border-[#56bbf1]/30">
                                        <i class="ph-fill ph-medal text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-[10px] font-black text-white uppercase tracking-widest mb-1">Apresiasi Sistem Otomatis</h4>
                                        <p class="text-xs text-[#56bbf1] font-medium leading-relaxed">Siswa mencapai poin kebaikan luar biasa. Tiket ini untuk apresiasi / bimbingan lanjutan.</p>
                                    </div>
                                </div>
                            @endif
                        @endif
                        
                        <div class="relative mb-4">
                            <div class="absolute -top-4 -left-2 text-5xl text-[#56bbf1]/20 font-serif opacity-50 print:hidden">“</div>
                            <div class="relative z-10 bg-[#021124]/90 p-5 rounded-2xl border border-white/10 text-slate-300 italic font-medium leading-relaxed text-sm">
                                {!! nl2br(e($session->initial_message)) !!}
                            </div>
                        </div>
                        
                        <div class="mt-6 flex items-center justify-end gap-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <i class="ph-bold ph-clock text-sm"></i> 
                            Diajukan: <span class="text-white">{{ $session->created_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- KOLOM KANAN: AKSI & JURNAL                 -->
                <!-- ========================================== -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- 1. FORM APPROVAL & TEMPLATE WA (Action Card - Hanya Pending) -->
                    @if($session->status == 'pending')
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-8 shadow-2xl border border-white/10 relative overflow-hidden print:hidden" 
                         x-data="{ 
                            action: '{{ $session->method == 'online' ? 'ongoing' : 'approved' }}',
                            responseMsg: '',
                            studentName: '{{ addslashes($session->student->name ?? 'Siswa') }}',
                            
                            setTemplate(type) {
                                if(type === 'panggilan') {
                                    this.responseMsg = `Yth. Bapak/Ibu Orang Tua/Wali dari ${this.studentName},\n\nKami mengundang kehadiran Bapak/Ibu ke sekolah (Ruang BK) pada jadwal yang telah kami tentukan untuk mendiskusikan laporan evaluasi kedisiplinan ananda.\n\nAtas perhatian dan kehadirannya kami ucapkan terima kasih.`;
                                } else if(type === 'apresiasi') {
                                    this.responseMsg = `Yth. Bapak/Ibu Orang Tua/Wali dari ${this.studentName},\n\nKami ingin menyampaikan apresiasi dari pihak sekolah terkait pencapaian positif terkait kehadiran ananda baru-baru ini. Mari kita terus dukung ananda agar semakin berprestasi!\n\nSalam hangat dari sekolah.`;
                                } else if(type === 'teguran') {
                                    this.responseMsg = `Yth. Bapak/Ibu Orang Tua/Wali dari ${this.studentName},\n\nMelalui pesan ini kami ingin menginformasikan evaluasi kedisiplinan ananda di sekolah. Mohon kerja samanya di rumah untuk memberikan arahan dan bimbingan.\n\nTerima kasih.`;
                                } else if(type === 'umum') {
                                    this.responseMsg = `Halo ${this.studentName},\n\nPengajuan konseling kamu telah kami terima dan disetujui. Silakan datang ke ruang BK tepat waktu sesuai jadwal yang dilampirkan ya. Semangat!`;
                                } else {
                                    this.responseMsg = '';
                                }
                            },
                            
                            copyToClipboard() {
                                if (!this.responseMsg) return;
                                navigator.clipboard.writeText(this.responseMsg).then(() => {
                                    Swal.fire({
                                        icon: 'success', title: 'Teks Disalin!', text: 'Template siap di-paste ke WhatsApp.',
                                        toast: true, position: 'top-end', showConfirmButton: false, timer: 2000,
                                        background: '#021124', color: '#ffffff',
                                        customClass: { popup: 'rounded-2xl border border-white/15 shadow-lg font-sans' }
                                    });
                                }).catch(err => { console.error('Gagal menyalin: ', err); });
                            }
                         }">
                        
                        <h3 class="text-sm font-black text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                            <div class="p-2 bg-[#56bbf1]/10 rounded-lg text-[#56bbf1] border border-[#56bbf1]/20">
                                <i class="ph-fill ph-gavel text-xl"></i>
                            </div>
                            Tindakan Guru BK
                        </h3>

                        <form action="{{ route('admin.bk.update_status', $session->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Keputusan</label>
                                    <div class="relative">
                                        <select name="status" x-model="action" class="w-full rounded-xl border-white/15 bg-[#021124] font-bold text-sm text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all py-3 px-4 appearance-none cursor-pointer">
                                            @if($session->method == 'online')
                                                <option value="ongoing" class="bg-[#021124] text-white">Mulai Sesi Chat (Online)</option>
                                            @else
                                                <option value="approved" class="bg-[#021124] text-white">Jadwalkan Pertemuan (Offline)</option>
                                            @endif
                                            <option value="finished" class="bg-[#021124] text-white">Selesaikan Langsung (Apresiasi/Pesan)</option>
                                            <option value="rejected" class="bg-[#021124] text-white">Tolak Pengajuan</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                                
                                <!-- Field Jadwal -->
                                <div x-show="action === 'approved'" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih Jadwal Pertemuan</label>
                                    <input type="datetime-local" name="scheduled_at" 
                                           min="{{ now()->startOfDay()->format('Y-m-d\TH:i') }}"
                                           class="w-full px-4 py-3 rounded-xl border-white/15 bg-[#021124] text-sm font-bold text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all shadow-sm" 
                                           :required="action === 'approved'">
                                </div>
                            </div>

                            <div class="mb-8 bg-white/5 p-6 rounded-2xl border border-white/10">
                                <div class="flex justify-between items-end mb-4">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                        <span x-text="action === 'rejected' ? 'Alasan Penolakan' : 'Pesan / Pemberitahuan (Auto-WA)'"></span>
                                    </label>
                                    
                                    <!-- Tombol Salin -->
                                    <button type="button" @click="copyToClipboard()" x-show="action !== 'rejected' && responseMsg.length > 0" class="text-[10px] font-black text-[#56bbf1] flex items-center gap-1 hover:text-white transition uppercase tracking-wider bg-white/10 px-2 py-1 rounded-md border border-white/15 shadow-sm">
                                        <i class="ph-bold ph-copy"></i> Salin Teks
                                    </button>
                                </div>

                                <!-- Template Buttons -->
                                <div x-show="action !== 'rejected'" class="flex flex-wrap gap-2 mb-4" x-transition>
                                    <button type="button" @click="setTemplate('panggilan')" x-show="action === 'approved'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-rose-500/20 text-rose-300 border border-rose-500/30 hover:bg-rose-500/30 transition-colors shadow-sm uppercase tracking-wide">
                                        <i class="ph-bold ph-warning"></i> Panggilan Ortu
                                    </button>
                                    <button type="button" @click="setTemplate('umum')" x-show="action === 'approved'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-white/10 text-slate-300 border border-white/15 hover:bg-white/20 transition-colors shadow-sm uppercase tracking-wide">
                                        <i class="ph-bold ph-chat-text"></i> Info ke Siswa
                                    </button>
                                    <button type="button" @click="setTemplate('apresiasi')" x-show="action === 'finished'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 hover:bg-[#56bbf1]/30 transition-colors shadow-sm uppercase tracking-wide">
                                        <i class="ph-bold ph-medal"></i> Apresiasi Prestasi
                                    </button>
                                    <button type="button" @click="setTemplate('teguran')" x-show="action === 'finished'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-500/30 hover:bg-amber-500/30 transition-colors shadow-sm uppercase tracking-wide">
                                        <i class="ph-bold ph-warning"></i> Teguran Ringan
                                    </button>
                                    <button type="button" @click="setTemplate('kosong')" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-white/10 text-slate-400 hover:text-rose-400 transition-colors uppercase tracking-wide">
                                        <i class="ph-bold ph-eraser"></i> Hapus
                                    </button>
                                </div>

                                <textarea name="response_message" x-model="responseMsg" rows="5" class="w-full px-4 py-3 rounded-xl border-white/15 bg-[#021124] text-sm font-medium text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all shadow-sm placeholder:text-slate-500" placeholder="Tulis atau pilih template pesan di sini..." required></textarea>
                                
                                <p class="text-[10px] font-bold text-slate-400 mt-3 flex items-center gap-1.5 uppercase tracking-wider ml-1">
                                    <i class="ph-fill ph-info text-[#56bbf1] text-sm"></i> 
                                    Pesan akan terkirim otomatis via Notifikasi WhatsApp.
                                </p>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-[#56bbf1] to-blue-600 text-white hover:brightness-110 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-[#56bbf1]/20 transition-all flex items-center justify-center gap-2 active:scale-95 group/btn">
                                <i class="ph-bold ph-paper-plane-right text-lg group-hover/btn:-translate-y-1 group-hover/btn:translate-x-1 transition-transform"></i> 
                                <span x-text="action === 'finished' ? 'Selesaikan Sesi' : 'Simpan & Kirim Notifikasi'"></span>
                            </button>
                        </form>
                    </div>
                    @endif

                    {{-- 2. BLOK STATUS: REJECTED --}}
                    @if($session->status == 'rejected')
                    <div class="bg-rose-500/10 rounded-[2rem] p-6 border border-rose-500/20 flex flex-col md:flex-row items-start gap-4 break-inside-avoid shadow-2xl">
                        <div class="p-3 bg-rose-500/20 rounded-2xl text-rose-400 shadow-sm shrink-0 print:hidden border border-rose-500/30">
                            <i class="ph-duotone ph-x-circle text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-rose-300 uppercase tracking-wider mb-1">Pengajuan Ditolak</h3>
                            <p class="text-rose-200/90 font-medium mt-2 text-sm leading-relaxed bg-[#021124]/90 p-4 rounded-xl border border-white/10 italic">
                                "{{ $session->response_message }}"
                            </p>
                            <div class="mt-4 text-[10px] font-bold text-rose-400 flex items-center gap-1 uppercase tracking-widest">
                                <i class="ph-bold ph-clock text-xs"></i> Diproses pada: {{ $session->updated_at->translatedFormat('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- 3. RUANG CHAT AKTIF (Jika Sesi Berlangsung Online) --}}
                    @if($session->status == 'ongoing' && $session->method == 'online')
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden flex flex-col h-[600px] animate-in zoom-in-95 duration-300 print:hidden">
                        <!-- Header Chat -->
                        <div class="p-5 sm:p-6 bg-gradient-to-r from-[#031d3d] to-[#021124] text-white flex justify-between items-center z-10 shadow-md relative overflow-hidden border-b border-white/10">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-[#56bbf1]/10 rounded-full blur-2xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                            <div class="flex items-center gap-3 md:gap-4 relative z-10">
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 flex items-center justify-center border border-white/20 backdrop-blur-sm"><i class="ph-fill ph-chats-circle text-xl md:text-2xl text-[#56bbf1]"></i></div>
                                <div>
                                    <h3 class="font-black text-sm md:text-base leading-tight text-white">Ruang Bimbingan Digital</h3>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                                        <p class="text-[9px] md:text-[10px] font-bold text-[#56bbf1] uppercase tracking-widest">Sesi Aktif Bersama {{ current(explode(' ', $session->student->name)) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <form action="{{ route('admin.bk.update_status', $session->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengakhiri sesi chat ini dan melanjutkan ke pengisian jurnal? Siswa tidak akan bisa membalas lagi.');" class="relative z-10">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="finished">
                                <button type="submit" class="px-4 py-2.5 bg-rose-500 text-white text-[10px] md:text-xs font-black uppercase tracking-widest rounded-xl hover:bg-rose-600 transition-colors shadow-lg shadow-rose-900/20 flex items-center gap-1.5 active:scale-95">
                                    <i class="ph-bold ph-power text-sm"></i> <span class="hidden sm:inline">Akhiri Sesi</span>
                                </button>
                            </form>
                        </div>

                        <!-- Area Chat -->
                        <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 bg-[#020b18]/80 custom-scrollbar" x-ref="chatBox">
                            <div class="text-center py-4 opacity-50" x-show="messages.length === 0">
                                <span class="bg-white/10 border border-white/15 text-slate-300 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">Mulai Percakapan</span>
                            </div>

                            <template x-for="msg in messages" :key="msg.id">
                                <div :class="msg.sender_type === 'teacher' ? 'flex justify-end' : 'flex justify-start'">
                                    <div :class="msg.sender_type === 'teacher' ? 'bg-[#56bbf1] text-[#020b18] rounded-2xl rounded-tr-sm shadow-md font-medium' : 'bg-[#021124] text-slate-100 border border-white/10 rounded-2xl rounded-tl-sm shadow-md'"
                                         class="max-w-[85%] sm:max-w-[75%] p-3 sm:p-4 relative group animate-in slide-in-from-bottom-2 duration-300">
                                        <p class="text-sm leading-relaxed font-medium break-words" x-text="msg.message"></p>
                                        <div class="flex justify-end items-center gap-1 mt-1 opacity-70">
                                            <span class="text-[8px] sm:text-[9px] font-bold tracking-widest" x-text="formatTime(msg.created_at)"></span>
                                            <template x-if="msg.sender_type === 'teacher'">
                                                <i class="ph-bold ph-checks text-[10px]"></i>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Input Chat -->
                        <div class="p-4 sm:p-5 bg-[#021124] border-t border-white/10 z-10">
                            <div class="flex gap-2 sm:gap-3 relative">
                                <input type="text" x-model="newMessage" @keydown.enter="send()" placeholder="Ketik pesan balasan..." 
                                    class="flex-1 rounded-2xl border-white/15 bg-[#020b18] text-sm font-medium text-white focus:border-[#56bbf1] focus:ring-[#56bbf1] px-4 py-3 sm:py-4 transition-all shadow-sm placeholder:text-slate-500">
                                <button @click="send()" :disabled="isSending" class="w-12 h-12 sm:w-14 sm:h-14 bg-[#56bbf1] text-[#020b18] rounded-2xl flex items-center justify-center hover:bg-[#56bbf1]/80 shadow-lg shadow-[#56bbf1]/20 transition-all disabled:opacity-50 active:scale-95 shrink-0 group/btn">
                                    <i class="ph-bold ph-paper-plane-right text-lg sm:text-xl group-hover/btn:-translate-y-0.5 group-hover/btn:translate-x-0.5 transition-transform" x-show="!isSending"></i>
                                    <i class="ph-bold ph-spinner animate-spin text-lg sm:text-xl" x-show="isSending" x-cloak></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- 4. BLOK STATUS: JADWAL (Approved/Finished) & INFO BALASAN GURU --}}
                    @if($session->status == 'approved' || $session->status == 'finished')
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-6 md:p-8 shadow-2xl border border-white/10 relative overflow-hidden break-inside-avoid">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-[#56bbf1] print:hidden"></div>
                         
                        <div class="flex flex-col lg:flex-row justify-between items-start gap-6">
                            <div class="flex-1">
                                <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 mb-6">
                                    <i class="ph-duotone ph-calendar-check text-[#56bbf1] text-xl print:hidden"></i> 
                                    {{ $session->scheduled_at ? 'Sesi Terjadwal' : 'Pemberitahuan Selesai' }}
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-[#56bbf1]/10 flex items-center justify-center text-[#56bbf1] border border-[#56bbf1]/20 shrink-0">
                                            <i class="ph-bold ph-clock text-lg"></i>
                                        </div>
                                        <div class="pt-1">
                                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Waktu Pertemuan</div>
                                            <div class="font-bold text-white text-sm">
                                                @if($session->scheduled_at)
                                                    {{ $session->scheduled_at->translatedFormat('l, d F Y - H:i') }} WIB
                                                @else
                                                    <span class="text-emerald-400">Pemberitahuan Langsung (Tanpa Tatap Muka)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 shrink-0">
                                            <i class="ph-bold ph-chat-centered-text text-lg"></i>
                                        </div>
                                        <div class="pt-1 w-full">
                                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pesan Tanggapan (Ke Siswa)</div>
                                            <div class="font-medium text-slate-300 text-sm italic whitespace-pre-line leading-relaxed bg-[#021124]/90 p-4 rounded-xl border border-white/10">"{{ $session->response_message }}"</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="shrink-0 pt-2 lg:pt-0">
                                @if($session->status == 'approved')
                                    <div class="px-4 py-2 bg-[#56bbf1]/10 text-[#56bbf1] border border-[#56bbf1]/20 rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center gap-2 animate-pulse print:hidden shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-[#56bbf1]"></span> Sedang Berlangsung
                                    </div>
                                @else
                                    <div class="px-4 py-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-sm">
                                        <i class="ph-fill ph-check-circle text-sm"></i> Selesai
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- 5. FORM JURNAL & HASIL RATING SISWA --}}
                    @if($session->status == 'approved' || $session->status == 'finished')
                    @php $isLocked = ($session->status == 'finished'); @endphp
                    
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-6 md:p-8 shadow-2xl border {{ $isLocked ? 'border-emerald-500/30' : 'border-white/10' }} mt-6 break-inside-avoid relative overflow-hidden" x-data="{ fileName: '' }">
                        @if($isLocked)
                            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-bl-full pointer-events-none -z-0"></div>
                        @endif

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-white/10 pb-6 print:border-black relative z-10">
                            <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-3">
                                <div class="p-2.5 bg-[#56bbf1]/10 border border-[#56bbf1]/20 rounded-xl text-[#56bbf1] print:hidden shadow-sm">
                                    <i class="ph-fill ph-notebook text-xl"></i>
                                </div>
                                Jurnal Hasil Konseling
                            </h3>
                            
                            @if($isLocked)
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] bg-white/10 text-slate-300 px-3 py-1.5 rounded-lg border border-white/15 font-black flex items-center gap-1.5 uppercase tracking-widest print:hidden shadow-sm">
                                        <i class="ph-fill ph-lock-key text-emerald-400"></i> Terkunci
                                    </span>
                                </div>
                            @endif
                        </div>

                        <form id="jurnalForm" action="{{ route('admin.bk.store_record', $session->id) }}" method="POST" enctype="multipart/form-data" class="relative z-10">
                            @csrf
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Analisis Masalah / Diagnosa</label>
                                    <textarea name="problem_analysis" rows="3" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'" 
                                              class="w-full px-5 py-4 rounded-2xl border-white/15 text-sm font-medium text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all shadow-sm resize-none overflow-hidden placeholder:text-slate-500 {{ $isLocked ? 'input-locked' : 'bg-[#021124]' }}" 
                                              placeholder="Jelaskan akar permasalahan siswa secara detail..." {{ $isLocked ? 'readonly' : 'required' }}>{{ $session->record->problem_analysis ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Solusi / Tindakan Diberikan</label>
                                    <textarea name="solution" rows="3" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'" 
                                              class="w-full px-5 py-4 rounded-2xl border-white/15 text-sm font-medium text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all shadow-sm resize-none overflow-hidden placeholder:text-slate-500 {{ $isLocked ? 'input-locked' : 'bg-[#021124]' }}" 
                                              placeholder="Nasihat, perlakuan, atau tindakan yang diberikan..." {{ $isLocked ? 'readonly' : 'required' }}>{{ $session->record->solution ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Hasil Akhir (Follow Up)</label>
                                    <textarea name="result" rows="2" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'" 
                                              class="w-full px-5 py-4 rounded-2xl border-white/15 text-sm font-medium text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all shadow-sm resize-none overflow-hidden placeholder:text-slate-500 {{ $isLocked ? 'input-locked' : 'bg-[#021124]' }}" 
                                              placeholder="Kesepakatan bersama atau rencana tindak lanjut..." {{ $isLocked ? 'readonly' : '' }}>{{ $session->record->result ?? '' }}</textarea>
                                </div>
                                
                                {{-- UPLOAD FILE UI / PREVIEW --}}
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Lampiran Dokumen <span class="normal-case font-medium">(Surat Dokter, Foto, dll)</span></label>
                                    
                                    @if($session->record && $session->record->attachment_path)
                                        <!-- Menampilkan file yang sudah diupload -->
                                        <div class="flex items-center justify-between p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-emerald-500/20 rounded-lg text-emerald-400"><i class="ph-fill ph-file-pdf text-2xl"></i></div>
                                                <div>
                                                    <p class="text-sm font-bold text-emerald-300">Dokumen Terlampir</p>
                                                    <p class="text-[10px] text-emerald-400 uppercase tracking-wider">Aman di Server</p>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $session->record->attachment_path) }}" target="_blank" class="px-4 py-2 bg-emerald-500 text-white rounded-xl text-xs font-bold hover:bg-emerald-600 shadow-sm print:hidden">Buka File</a>
                                        </div>
                                    @elseif(!$isLocked)
                                        <!-- UI Upload Drag & Drop -->
                                        <div class="file-drop-area relative flex flex-col items-center justify-center w-full h-32 rounded-2xl bg-[#021124] group cursor-pointer overflow-hidden mb-4">
                                            <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileName = $event.target.files[0].name">
                                            
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-slate-400 group-hover:text-[#56bbf1] transition-colors">
                                                <i class="ph-duotone ph-upload-simple text-3xl mb-2" x-show="!fileName"></i>
                                                <i class="ph-fill ph-file-text text-3xl mb-2 text-[#56bbf1]" x-show="fileName" x-cloak></i>
                                                <p class="text-sm font-bold" x-show="!fileName">Klik atau seret file ke sini</p>
                                                <p class="text-sm font-bold text-[#56bbf1]" x-show="fileName" x-text="fileName" x-cloak></p>
                                                <p class="text-[10px] uppercase tracking-widest mt-1" x-show="!fileName">PDF, JPG, PNG (Maks 2MB)</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-4 bg-white/5 border border-white/10 border-dashed rounded-2xl text-center text-sm font-medium text-slate-400 mb-4">Tidak ada file lampiran disertakan.</div>
                                    @endif
                                </div>
                                
                                @if(!$isLocked)
                                    <label class="flex items-center gap-4 p-5 rounded-2xl border border-white/15 bg-[#021124] hover:bg-[#021124]/80 cursor-pointer transition-all group print:hidden shadow-sm">
                                        <input type="checkbox" name="is_confidential" value="1" {{ isset($session->record) && $session->record->is_confidential ? 'checked' : '' }} class="w-5 h-5 rounded border-white/20 bg-[#020b18] text-[#56bbf1] focus:ring-[#56bbf1] transition-colors shadow-sm">
                                        <div>
                                            <div class="text-sm font-black text-white group-hover:text-[#56bbf1] transition-colors uppercase tracking-wider mb-0.5">Bersifat Rahasia (Confidential)</div>
                                            <div class="text-xs text-slate-400 font-medium">Hanya Guru BK & Kepala Sekolah yang dapat melihat catatan ini.</div>
                                        </div>
                                    </label>

                                    <div class="pt-6 mt-4 print:hidden">
                                        <button type="button" onclick="confirmJurnal()" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/20 flex justify-center items-center gap-2 transition-all transform active:scale-95">
                                            <i class="ph-bold ph-check-circle text-lg"></i> Simpan & Selesaikan Sesi
                                        </button>
                                    </div>
                                @else
                                    {{-- Cap Confidential Jika Selesai & Rahasia --}}
                                    @if($session->record && $session->record->is_confidential)
                                        <div class="flex items-center gap-2 px-4 py-2 bg-rose-500/20 text-rose-300 rounded-xl font-black uppercase tracking-widest text-[10px] border border-rose-500/30 w-fit print:hidden shadow-sm mt-4">
                                            <i class="ph-fill ph-lock-key text-sm"></i> Dokumen Rahasia
                                        </div>
                                        <div class="hidden print:block mt-8 text-center text-rose-700 font-bold uppercase text-2xl border-4 border-rose-700 px-4 py-2 w-max mx-auto opacity-50 rotate-[-15deg]">
                                            CONFIDENTIAL / RAHASIA
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </form>

                        {{-- TAMPILAN RATING & EVALUASI SISWA --}}
                        @if($isLocked)
                            <div class="mt-10 pt-8 border-t border-white/10 print:hidden relative z-10">
                                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 mb-6">
                                    <i class="ph-fill ph-star text-amber-400 text-base"></i> Evaluasi Pelayanan (Dari Siswa)
                                </h3>
                                @if($session->rating)
                                    <div class="bg-amber-500/10 rounded-[2rem] p-6 border border-amber-500/20 shadow-sm relative overflow-hidden group hover:bg-amber-500/15 transition-colors">
                                        <i class="ph-fill ph-quotes absolute right-4 bottom-4 text-6xl text-amber-500/10"></i>
                                        <div class="flex items-center gap-1.5 text-amber-400 text-2xl mb-4 relative z-10">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="{{ $i <= $session->rating ? 'ph-fill' : 'ph-bold text-slate-600' }} ph-star drop-shadow-sm"></i>
                                            @endfor
                                            <span class="font-black text-amber-300 ml-3 text-xl">{{ $session->rating }}/5</span>
                                        </div>
                                        @if($session->student_feedback)
                                            <div class="bg-[#021124] p-5 rounded-2xl border border-amber-500/20 text-amber-200 italic font-medium relative z-10 shadow-sm text-sm leading-relaxed">
                                                "{{ $session->student_feedback }}"
                                            </div>
                                        @else
                                            <p class="text-amber-400/80 text-sm font-medium italic relative z-10">Siswa memberikan rating bintang tanpa ulasan teks.</p>
                                        @endif
                                        <p class="text-[9px] font-black text-amber-400/60 mt-4 uppercase tracking-[0.2em] relative z-10">
                                            Dinilai pada: {{ \Carbon\Carbon::parse($session->feedback_at)->translatedFormat('d M Y, H:i') }}
                                        </p>
                                    </div>
                                @else
                                    <div class="bg-white/5 rounded-[2rem] p-8 border border-white/10 text-center border-dashed">
                                        <i class="ph-duotone ph-hourglass-high text-4xl text-slate-500 mb-3"></i>
                                        <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Menunggu Penilaian</p>
                                        <p class="text-xs text-slate-400 mt-2 max-w-sm mx-auto font-medium">Siswa belum mengisi survei kepuasan layanan.</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>
                    @endif

                </div>
            </div>
            
            {{-- BAGIAN TANDA TANGAN (HANYA MUNCUL SAAT DI-PRINT) --}}
            <div class="hidden print:flex justify-between items-end mt-16 px-8 break-inside-avoid">
                <div class="text-center">
                    <p class="text-sm font-medium mb-16">Mengetahui,<br>Kepala Sekolah</p>
                    <p class="text-sm font-bold underline decoration-1 underline-offset-2">_________________________</p>
                    <p class="text-xs mt-1">NIP. ..............................</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium mb-16">Kota/Kabupaten, {{ now()->translatedFormat('d F Y') }}<br>Guru Bimbingan Konseling</p>
                    <p class="text-sm font-bold underline decoration-1 underline-offset-2">{{ Auth::user()->name ?? '_________________________' }}</p>
                    <p class="text-xs mt-1">NIP. ..............................</p>
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Logika Pop-Up Konfirmasi Jurnal
        function confirmJurnal() {
            Swal.fire({
                title: 'Selesaikan Sesi?',
                html: "Jurnal yang disimpan akan bersifat <b class='text-rose-400'>Read-Only (terkunci)</b> sebagai arsip resmi sekolah dan tidak dapat diubah kembali.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981', // emerald-500
                cancelButtonColor: '#64748b',  // slate-500
                confirmButtonText: 'Ya, Selesaikan',
                cancelButtonText: 'Batal',
                background: '#021124',
                color: '#ffffff',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/15 shadow-2xl',
                    confirmButton: 'rounded-xl font-bold px-6 py-3',
                    cancelButton: 'rounded-xl font-bold px-6 py-3'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('jurnalForm').submit();
                }
            });
        }

        // Flash Messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: "{!! session('success') !!}",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                    background: '#021124', color: '#ffffff',
                    customClass: { popup: 'rounded-2xl border border-white/15 shadow-lg font-sans' }
                });
            @endif
            @if(session('error'))
                Swal.fire({
                    icon: 'error', title: 'Oops...', text: "{!! session('error') !!}",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
                    background: '#021124', color: '#ffffff',
                    customClass: { popup: 'rounded-2xl border border-white/15 shadow-lg font-sans' }
                });
            @endif
        });

        // Chat Handler
        function bkTeacherChatHandler(sessionId) {
            return {
                messages: [], newMessage: '', isSending: false,
                init() { 
                    this.fetchMessages(); 
                    setInterval(() => {
                        if (!document.hidden) {
                            this.fetchMessages();
                        }
                    }, 2000); 
                },
                fetchMessages() {
                    fetch(`/admin/bk/chat/${sessionId}`, { headers: { 'Accept': 'application/json' } })
                        .then(res => res.json())
                        .then(data => {
                            let isNew = this.messages.length !== data.length;
                            this.messages = data;
                            if(isNew) this.scrollToBottom();
                        }).catch(e => console.warn("Polling error:", e));
                },
                send() {
                    if (!this.newMessage.trim() || this.isSending) return;
                    let text = this.newMessage; this.newMessage = ''; this.isSending = true;
                    
                    this.messages.push({ id: 'temp-'+Date.now(), message: text, sender_type: 'teacher', created_at: new Date().toISOString() });
                    this.scrollToBottom();
                    
                    fetch(`/admin/bk/chat/${sessionId}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ message: text })
                    }).then(res => { if(!res.ok) throw new Error('Error'); return res.json(); })
                      .then(data => { this.isSending = false; this.fetchMessages(); })
                      .catch(err => { this.isSending = false; this.messages.pop(); Swal.fire({ title: 'Gagal!', text: 'Tidak dapat mengirim pesan', icon: 'error', background: '#021124', color: '#ffffff' }); });
                },
                scrollToBottom() { this.$nextTick(() => { let b = this.$refs.chatBox; if(b) b.scrollTop = b.scrollHeight; }); },
                formatTime(iso) { if(!iso) return ''; return new Date(iso).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}); }
            }
        }
    </script>
</x-app-layout>