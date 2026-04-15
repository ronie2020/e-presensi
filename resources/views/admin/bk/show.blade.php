<x-app-layout>
    {{-- 
        REDESIGN DETAIL KONSELING (BLUE THEME)
        - Diperbarui dengan UI Alert khusus untuk tiket hasil integrasi Sistem Disiplin
        - DITAMBAHKAN: Fitur Template Cepat (Quick Templates) Pesan WA
        - DITAMBAHKAN: Fitur Cetak Dokumen & Print Styles CSS
        - DITAMBAHKAN: Validasi Tanggal dan Konfirmasi Simpan Jurnal
        - DITAMBAHKAN (BARU): Kop Surat Print, Visual Tracker, Auto-expand Textarea, Copy Teks WA.
        - DITAMBAHKAN (BARU): Fitur "Pemberitahuan Langsung / Apresiasi" Tanpa Jadwal.
        - TERINTEGRASI: Ruang Chat Online AlpineJS & Tampilan Rating Siswa.
    --}}

    <style>
        /* CSS Khusus untuk Mode Cetak (Print / Save as PDF) */
        @media print {
            body { background-color: white !important; }
            .print\:hidden { display: none !important; }
            .print\:block { display: block !important; }
            .shadow-xl, .shadow-sm, .shadow-md, .shadow-lg { box-shadow: none !important; }
            .border { border: 1px solid #cbd5e1 !important; }
            .bg-white, .bg-slate-50, .bg-blue-50, .bg-rose-50, .bg-indigo-50 { background-color: white !important; }
            /* Memaksa elemen untuk tidak terpotong di tengah halaman */
            .break-inside-avoid { break-inside: avoid; } 
            @page { margin: 1.5cm; }
        }
        
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
        [x-cloak] { display: none !important; }
        
        /* Style untuk input yang dikunci (Jurnal Selesai) */
        .input-locked {
            background-color: #f8fafc !important;
            color: #475569 !important;
            cursor: not-allowed;
            border-style: dashed !important;
        }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-800" x-data="bkTeacherChatHandler({{ $session->id }})">
        
        {{-- ========================================================= --}}
        {{-- KOP SURAT (HANYA MUNCUL SAAT DI-PRINT / CETAK PDF)        --}}
        {{-- ========================================================= --}}
        <div class="hidden print:block w-full border-b-4 border-double border-slate-800 pb-4 mb-8 text-center">
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
                    <div class="flex items-center gap-2 text-sm font-bold text-blue-600 mb-1 uppercase tracking-wider">
                        <i class="ph-fill ph-hash"></i> Sesi Konseling {{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Proses & Tindak Lanjut</h1>
                    <p class="text-slate-500 font-medium">Kelola status pengajuan dan rekam hasil konseling siswa.</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    {{-- TOMBOL CETAK (Hanya Muncul Jika Selesai) --}}
                    @if($session->status == 'finished')
                        <button onclick="window.print()" class="group flex items-center gap-2 px-5 py-2.5 bg-indigo-50 border border-indigo-200 rounded-2xl text-indigo-700 font-bold hover:bg-indigo-600 hover:text-white shadow-sm hover:shadow-md transition-all">
                            <i class="ph-bold ph-printer group-hover:animate-bounce"></i>
                            Cetak Jurnal
                        </button>
                    @endif

                    <a href="{{ route('admin.bk.index') }}" class="group flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-slate-600 font-bold hover:border-blue-400 hover:text-blue-600 shadow-sm hover:shadow-md transition-all">
                        <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- VISUAL STATUS TRACKER (TIMELINE) --}}
            <div class="mt-8 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between relative overflow-hidden">
                <div class="absolute top-1/2 left-8 right-8 h-1 bg-slate-100 -translate-y-1/2 rounded-full z-0"></div>
                
                @php
                    $isApproved = in_array($session->status, ['approved', 'finished', 'ongoing']);
                    $isFinished = $session->status == 'finished';
                    $isRejected = $session->status == 'rejected';
                @endphp

                <!-- Step 1: Pengajuan -->
                <div class="relative z-10 flex flex-col items-center bg-white px-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white shadow-md bg-blue-500 ring-4 ring-white">
                        <i class="ph-bold ph-check"></i>
                    </div>
                    <div class="text-[10px] font-bold text-slate-800 uppercase mt-2">Pengajuan</div>
                </div>

                <!-- Step 2: Respon -->
                @if($isRejected)
                    <div class="relative z-10 flex flex-col items-center bg-white px-4">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white shadow-md bg-rose-500 ring-4 ring-white">
                            <i class="ph-bold ph-x"></i>
                        </div>
                        <div class="text-[10px] font-bold text-rose-600 uppercase mt-2">Ditolak</div>
                    </div>
                @else
                    <div class="relative z-10 flex flex-col items-center bg-white px-4">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white shadow-md ring-4 ring-white {{ $isApproved ? 'bg-blue-500' : 'bg-slate-200 text-slate-400' }}">
                            @if($isApproved) <i class="ph-bold ph-check"></i> @else <span class="text-xs">2</span> @endif
                        </div>
                        <div class="text-[10px] font-bold uppercase mt-2 {{ $isApproved ? 'text-slate-800' : 'text-slate-400' }}">Tanggapan BK</div>
                    </div>
                @endif

                <!-- Step 3: Selesai -->
                @if(!$isRejected)
                    <div class="relative z-10 flex flex-col items-center bg-white px-4">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white shadow-md ring-4 ring-white {{ $isFinished ? 'bg-emerald-500' : 'bg-slate-200 text-slate-400' }}">
                            @if($isFinished) <i class="ph-bold ph-check-circle"></i> @else <span class="text-xs">3</span> @endif
                        </div>
                        <div class="text-[10px] font-bold uppercase mt-2 {{ $isFinished ? 'text-emerald-600' : 'text-slate-400' }}">Selesai / Arsip</div>
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
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/60 border border-slate-100 relative overflow-hidden break-inside-avoid">
                        <div class="absolute top-0 right-0 p-6 opacity-5 print:hidden">
                            <i class="ph-duotone ph-student text-9xl text-blue-900"></i>
                        </div>
                        
                        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="ph-bold ph-user-circle"></i> Data Siswa
                        </h3>

                        <div class="flex flex-col items-center text-center mb-6 relative z-10">
                            <!-- Foto Profil -->
                            <div class="w-24 h-24 rounded-full p-1 bg-gradient-to-tr from-blue-500 to-purple-500 mb-4 shadow-lg shadow-blue-500/20 print:hidden">
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
                            <div class="text-sm font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full mt-2 border border-blue-100">
                                {{ $session->student->schoolClass->name ?? 'Tanpa Kelas' }}
                            </div>
                        </div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex justify-between items-center p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                <span class="text-xs font-bold text-slate-400 uppercase">NIS / NISN</span>
                                <span class="font-bold text-slate-700 font-mono">{{ $session->student->nis ?? '-' }} / {{ $session->student->student_id ?? '-' }}</span>
                            </div>
                            
                            <div class="print:hidden">
                                @if($session->student->parent_wa_number ?? false)
                                    @php
                                        $waMessage = "Salam hormat Bapak/Ibu Orang Tua/Wali dari " . ($session->student->name ?? '') . ",\n\nKami dari pihak Bimbingan Konseling sekolah ingin berdiskusi terkait ananda. Mohon konfirmasi ketersediaan Bapak/Ibu untuk komunikasi lebih lanjut. Terima kasih.";
                                        $waLink = "https://wa.me/" . preg_replace('/^0/', '62', $session->student->parent_wa_number) . "?text=" . urlencode($waMessage);
                                    @endphp
                                    <a href="{{ $waLink }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-emerald-50 text-emerald-600 font-bold rounded-xl border border-emerald-100 hover:bg-emerald-100 transition-colors">
                                        <i class="ph-fill ph-whatsapp-logo text-xl"></i> 
                                        Hubungi Orang Tua
                                    </a>
                                @else
                                    <div class="flex items-center justify-center gap-2 w-full py-3 bg-slate-50 text-slate-400 font-bold rounded-xl border border-slate-100 cursor-not-allowed">
                                        <i class="ph-slash ph-whatsapp-logo text-xl"></i> No. WA Tidak Ada
                                    </div>
                                @endif

                                @if(Route::has('admin.discipline.student_history'))
                                    <a href="{{ route('admin.discipline.student_history', $session->student->id) }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-indigo-50 text-indigo-600 font-bold rounded-xl border border-indigo-100 hover:bg-indigo-100 transition-colors mt-2">
                                        <i class="ph-bold ph-shield-warning text-xl"></i> 
                                        Lihat Rekam Disiplin
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- KARTU 2: Detail Pengajuan -->
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/60 border border-slate-100 break-inside-avoid">
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

                        {{-- ALERT INTEGRASI SISTEM DISIPLIN --}}
                        @if($session->is_system_generated)
                            @if(str_contains($session->initial_message, 'PELANGGARAN'))
                                <div class="mb-4 bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start gap-3">
                                    <div class="p-2 bg-rose-100 text-rose-600 rounded-lg animate-pulse print:hidden">
                                        <i class="ph-fill ph-warning-octagon text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-rose-800 uppercase tracking-wider mb-1">Panggilan Otomatis Sistem</h4>
                                        <p class="text-xs text-rose-700 font-medium">Tiket ini dibuat secara otomatis karena siswa telah mencapai ambang batas poin pelanggaran di modul Disiplin.</p>
                                    </div>
                                </div>
                            @elseif(str_contains($session->initial_message, 'PRESTASI'))
                                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg print:hidden">
                                        <i class="ph-fill ph-medal text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-blue-800 uppercase tracking-wider mb-1">Apresiasi Sistem Otomatis</h4>
                                        <p class="text-xs text-blue-700 font-medium">Siswa mencapai poin kebaikan luar biasa. Tiket ini dibuat untuk pemberian apresiasi / bimbingan lanjutan.</p>
                                    </div>
                                </div>
                            @endif
                        @endif
                        
                        <div class="relative mb-2">
                            <div class="absolute -top-3 -left-2 text-5xl text-blue-100 font-serif opacity-50 print:hidden">“</div>
                            <div class="relative z-10 bg-blue-50/50 p-5 rounded-2xl border border-blue-100 text-slate-700 italic font-medium leading-relaxed">
                                {!! nl2br(e($session->initial_message)) !!}
                            </div>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-end gap-1.5 text-xs font-bold text-slate-400">
                            <i class="ph-bold ph-clock"></i> 
                            Diajukan: {{ $session->created_at->translatedFormat('d M Y, H:i') }}
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- KOLOM KANAN: AKSI & JURNAL                 -->
                <!-- ========================================== -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- 1. FORM APPROVAL & TEMPLATE WA (Action Card - Hanya Pending) -->
                    @if($session->status == 'pending')
                    <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-amber-500/10 border border-amber-100 relative overflow-hidden print:hidden" 
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
                                        customClass: { popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans' }
                                    });
                                }).catch(err => { console.error('Gagal menyalin: ', err); });
                            }
                         }">
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
                                        <select name="status" x-model="action" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-sm">
                                            @if($session->method == 'online')
                                                <option value="ongoing">Mulai Sesi Chat (Online)</option>
                                            @else
                                                <option value="approved">Jadwalkan Pertemuan (Offline)</option>
                                            @endif
                                            <option value="finished">Selesaikan Langsung (Apresiasi/Pesan)</option>
                                            <option value="rejected">Tolak Pengajuan</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Field Jadwal -->
                                <div x-show="action === 'approved'" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Jadwal Pertemuan</label>
                                    <input type="datetime-local" name="scheduled_at" 
                                           min="{{ now()->format('Y-m-d\TH:i') }}"
                                           class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm" 
                                           :required="action === 'approved'">
                                </div>
                            </div>

                            <div class="mb-6">
                                <div class="flex justify-between items-end mb-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        <span x-text="action === 'rejected' ? 'Alasan Penolakan' : 'Pesan / Pemberitahuan (Auto-WA)'"></span>
                                    </label>
                                    
                                    <!-- Tombol Salin -->
                                    <button type="button" @click="copyToClipboard()" x-show="action !== 'rejected' && responseMsg.length > 0" class="text-[10px] font-bold text-blue-600 flex items-center gap-1 hover:text-blue-800 transition">
                                        <i class="ph-bold ph-copy"></i> Salin Teks
                                    </button>
                                </div>

                                <!-- Template Buttons -->
                                <div x-show="action !== 'rejected'" class="flex flex-wrap gap-2 mb-3" x-transition>
                                    <button type="button" @click="setTemplate('panggilan')" x-show="action === 'approved'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 transition-colors shadow-sm">
                                        <i class="ph-bold ph-warning"></i> Panggilan Ortu
                                    </button>
                                    <button type="button" @click="setTemplate('umum')" x-show="action === 'approved'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200 transition-colors shadow-sm">
                                        <i class="ph-bold ph-chat-text"></i> Info ke Siswa
                                    </button>
                                    <button type="button" @click="setTemplate('apresiasi')" x-show="action === 'finished'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 transition-colors shadow-sm">
                                        <i class="ph-bold ph-medal"></i> Apresiasi Prestasi
                                    </button>
                                    <button type="button" @click="setTemplate('teguran')" x-show="action === 'finished'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100 transition-colors shadow-sm">
                                        <i class="ph-bold ph-warning"></i> Teguran Ringan
                                    </button>
                                    <button type="button" @click="setTemplate('kosong')" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-white text-slate-400 border border-slate-200 hover:bg-slate-50 transition-colors shadow-sm">
                                        <i class="ph-bold ph-eraser"></i> Hapus
                                    </button>
                                </div>

                                <textarea name="response_message" x-model="responseMsg" rows="5" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm" placeholder="Tulis atau pilih template pesan di sini..." required></textarea>
                                
                                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1.5 font-medium">
                                    <i class="ph-fill ph-info text-blue-400"></i> 
                                    Isi pesan ini akan dikirimkan secara otomatis via Notifikasi WhatsApp.
                                </p>
                            </div>

                            <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2">
                                <i class="ph-bold ph-paper-plane-right text-lg"></i> 
                                <span x-text="action === 'finished' ? 'Kirim Pemberitahuan & Selesai' : 'Simpan & Kirim Notifikasi'"></span>
                            </button>
                        </form>
                    </div>
                    @endif

                    {{-- 2. BLOK STATUS: REJECTED --}}
                    @if($session->status == 'rejected')
                    <div class="bg-rose-50 rounded-[2rem] p-6 border border-rose-100 flex flex-col md:flex-row items-start gap-4 break-inside-avoid">
                        <div class="p-3 bg-white rounded-2xl text-rose-500 shadow-sm shrink-0 print:hidden">
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

                    {{-- 3. RUANG CHAT AKTIF (Jika Sesi Berlangsung Online) --}}
                    @if($session->status == 'ongoing' && $session->method == 'online')
                    <div class="bg-white rounded-[2.5rem] shadow-2xl border border-indigo-100 overflow-hidden flex flex-col h-[600px] animate-in zoom-in-95 duration-300 print:hidden">
                        <!-- Header Chat -->
                        <div class="p-5 sm:p-6 bg-slate-800 text-white flex justify-between items-center z-10 shadow-md">
                            <div class="flex items-center gap-3 md:gap-4">
                                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 flex items-center justify-center border border-white/20"><i class="ph-fill ph-chats-circle text-xl md:text-2xl"></i></div>
                                <div>
                                    <h3 class="font-black text-sm md:text-base leading-tight">Ruang Bimbingan Digital</h3>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                                        <p class="text-[9px] md:text-[10px] font-bold text-indigo-300 uppercase tracking-widest">Sesi Aktif Bersama {{ current(explode(' ', $session->student->name)) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <form action="{{ route('admin.bk.update_status', $session->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengakhiri sesi chat ini dan melanjutkan ke pengisian jurnal? Siswa tidak akan bisa membalas lagi.');">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="finished">
                                <button type="submit" class="px-4 py-2.5 bg-rose-500 text-white text-[10px] md:text-xs font-black uppercase rounded-xl hover:bg-rose-600 transition-colors shadow-lg shadow-rose-900/20 flex items-center gap-1.5">
                                    <i class="ph-bold ph-power"></i> <span class="hidden sm:inline">Akhiri Sesi</span>
                                </button>
                            </form>
                        </div>

                        <!-- Area Chat -->
                        <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 bg-slate-50/80 custom-scrollbar" x-ref="chatBox">
                            <div class="text-center py-4 opacity-50" x-show="messages.length === 0">
                                <span class="bg-slate-200 text-slate-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Mulai Percakapan</span>
                            </div>

                            <template x-for="msg in messages" :key="msg.id">
                                <div :class="msg.sender_type === 'teacher' ? 'flex justify-end' : 'flex justify-start'">
                                    <div :class="msg.sender_type === 'teacher' ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-sm shadow-indigo-200' : 'bg-white text-slate-800 border border-slate-200 rounded-2xl rounded-tl-sm shadow-slate-100'"
                                         class="max-w-[85%] sm:max-w-[75%] p-3 sm:p-4 shadow-md relative group animate-in slide-in-from-bottom-2 duration-300">
                                        <p class="text-sm leading-relaxed font-medium break-words" x-text="msg.message"></p>
                                        <div class="flex justify-end items-center gap-1 mt-1 opacity-60">
                                            <span class="text-[8px] sm:text-[9px] font-bold" x-text="formatTime(msg.created_at)"></span>
                                            <template x-if="msg.sender_type === 'teacher'">
                                                <i class="ph-bold ph-checks text-[10px]"></i>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Input Chat -->
                        <div class="p-4 sm:p-5 bg-white border-t border-slate-100 z-10">
                            <div class="flex gap-2 sm:gap-3 relative">
                                <input type="text" x-model="newMessage" @keydown.enter="send()" placeholder="Ketik pesan untuk siswa..." 
                                    class="flex-1 rounded-2xl border-slate-200 bg-slate-50 text-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 sm:py-4 transition-all">
                                <button @click="send()" :disabled="isSending" class="w-12 h-12 sm:w-14 sm:h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all disabled:opacity-50 active:scale-95 shrink-0">
                                    <i class="ph-bold ph-paper-plane-right text-lg sm:text-xl" x-show="!isSending"></i>
                                    <i class="ph-bold ph-spinner animate-spin text-lg sm:text-xl" x-show="isSending" x-cloak></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- 4. BLOK STATUS: JADWAL (Approved/Finished) & INFO BALASAN GURU --}}
                    @if($session->status == 'approved' || $session->status == 'finished')
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/60 border border-slate-100 relative overflow-hidden break-inside-avoid">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-500 print:hidden"></div>
                         
                        <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                            <div>
                                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                    <i class="ph-duotone ph-calendar-check text-blue-500 text-2xl print:hidden"></i> 
                                    {{ $session->scheduled_at ? 'Sesi Terjadwal' : 'Pemberitahuan Selesai' }}
                                </h3>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 print:border print:border-blue-200">
                                            <i class="ph-bold ph-clock"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-400 uppercase">Waktu Pertemuan</div>
                                            <div class="font-bold text-slate-800">
                                                @if($session->scheduled_at)
                                                    {{ $session->scheduled_at->translatedFormat('l, d F Y - H:i') }} WIB
                                                @else
                                                    <span class="text-emerald-600">Pemberitahuan Langsung (Tanpa Tatap Muka)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 pt-2">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0 print:border print:border-indigo-200">
                                            <i class="ph-bold ph-chat-centered-text"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-400 uppercase">Pesan & Informasi (Siswa)</div>
                                            <div class="font-medium text-slate-600 text-sm italic whitespace-pre-line leading-relaxed">"{{ $session->response_message }}"</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($session->status == 'approved')
                                <div class="px-4 py-2 bg-blue-100 text-blue-700 rounded-xl font-bold text-xs flex items-center gap-2 animate-pulse print:hidden">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Sedang Berlangsung
                                </div>
                            @else
                                <div class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl font-bold text-xs flex items-center gap-2 print:border print:border-emerald-200">
                                    <i class="ph-fill ph-check-circle"></i> Selesai
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- 5. FORM JURNAL & HASIL RATING SISWA --}}
                    @if($session->status == 'approved' || $session->status == 'finished')
                    @php $isLocked = ($session->status == 'finished'); @endphp
                    
                    <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/60 border {{ $isLocked ? 'border-emerald-200' : 'border-slate-100' }} mt-6 break-inside-avoid">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-slate-100 pb-4 print:border-black">
                            <h3 class="text-xl font-black text-slate-800 flex items-center gap-3">
                                <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-600 print:hidden">
                                    <i class="ph-fill ph-notebook text-xl"></i>
                                </div>
                                Jurnal Hasil Konseling
                            </h3>
                            
                            @if($isLocked)
                                <div class="flex items-center gap-3">
                                    <span class="text-xs bg-slate-100 text-slate-500 px-3 py-1.5 rounded-lg border border-slate-200 font-bold flex items-center gap-1.5 uppercase tracking-wide print:hidden">
                                        <i class="ph-fill ph-lock-key"></i> Read Only
                                    </span>
                                </div>
                            @endif
                        </div>

                        <form id="jurnalForm" action="{{ route('admin.bk.store_record', $session->id) }}" method="POST">
                            @csrf
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Analisis Masalah / Diagnosa</label>
                                    <textarea name="problem_analysis" rows="3" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'" 
                                              class="w-full px-4 py-3 rounded-xl border-slate-200 text-sm font-medium focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm resize-none overflow-hidden {{ $isLocked ? 'input-locked' : 'bg-slate-50 focus:bg-white' }}" 
                                              placeholder="Jelaskan akar permasalahan siswa secara detail..." {{ $isLocked ? 'readonly' : 'required' }}>{{ $session->record->problem_analysis ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Solusi / Tindakan Diberikan</label>
                                    <textarea name="solution" rows="3" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'" 
                                              class="w-full px-4 py-3 rounded-xl border-slate-200 text-sm font-medium focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm resize-none overflow-hidden {{ $isLocked ? 'input-locked' : 'bg-slate-50 focus:bg-white' }}" 
                                              placeholder="Nasihat, perlakuan, atau tindakan yang diberikan..." {{ $isLocked ? 'readonly' : 'required' }}>{{ $session->record->solution ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Hasil Akhir (Follow Up)</label>
                                    <textarea name="result" rows="2" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'" 
                                              class="w-full px-4 py-3 rounded-xl border-slate-200 text-sm font-medium focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm resize-none overflow-hidden {{ $isLocked ? 'input-locked' : 'bg-slate-50 focus:bg-white' }}" 
                                              placeholder="Kesepakatan bersama atau rencana tindak lanjut..." {{ $isLocked ? 'readonly' : '' }}>{{ $session->record->result ?? '' }}</textarea>
                                </div>
                                
                                @if(!$isLocked)
                                    <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 cursor-pointer transition-colors group print:hidden">
                                        <input type="checkbox" name="is_confidential" value="1" {{ isset($session->record) && $session->record->is_confidential ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-colors">
                                        <div>
                                            <div class="text-sm font-bold text-slate-700 group-hover:text-blue-700 transition-colors">Bersifat Rahasia (Confidential)</div>
                                            <div class="text-xs text-slate-400">Hanya Guru BK & Kepala Sekolah yang dapat melihat catatan ini.</div>
                                        </div>
                                    </label>

                                    <div class="pt-4 border-t border-slate-100 mt-4 print:hidden">
                                        <button type="button" onclick="confirmJurnal()" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-4 rounded-xl font-bold text-sm shadow-lg shadow-emerald-500/30 flex justify-center items-center gap-2 transition-all transform hover:scale-[1.01]">
                                            <i class="ph-bold ph-check-circle text-xl"></i> Simpan & Selesaikan Sesi
                                        </button>
                                    </div>
                                @else
                                    {{-- Cap Confidential Jika Selesai & Rahasia --}}
                                    @if($session->record && $session->record->is_confidential)
                                        <div class="flex items-center gap-2 px-4 py-3 bg-rose-50 text-rose-700 rounded-xl font-bold text-xs border border-rose-100 w-fit print:hidden">
                                            <i class="ph-fill ph-lock-key"></i> Dokumen Rahasia (Confidential)
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
                            <div class="mt-10 pt-8 border-t-2 border-dashed border-slate-100 print:hidden">
                                <h3 class="text-sm font-black text-slate-800 flex items-center gap-2 mb-6">
                                    <i class="ph-fill ph-star text-amber-500 text-xl"></i> Evaluasi Pelayanan (Siswa)
                                </h3>
                                @if($session->rating)
                                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-100 shadow-sm relative overflow-hidden">
                                        <i class="ph-fill ph-quotes absolute right-4 bottom-4 text-6xl text-amber-200 opacity-30"></i>
                                        <div class="flex items-center gap-2 text-amber-500 text-2xl mb-4 relative z-10">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="{{ $i <= $session->rating ? 'ph-fill' : 'ph-bold' }} ph-star"></i>
                                            @endfor
                                            <span class="font-black text-amber-800 ml-3 text-xl">{{ $session->rating }}/5</span>
                                        </div>
                                        @if($session->student_feedback)
                                            <div class="bg-white/70 p-4 rounded-xl border border-amber-200/50 text-amber-900 italic font-medium relative z-10 shadow-sm">
                                                "{{ $session->student_feedback }}"
                                            </div>
                                        @else
                                            <p class="text-amber-700/70 text-sm font-medium italic relative z-10">Siswa memberikan rating bintang tanpa ulasan teks.</p>
                                        @endif
                                        <p class="text-[10px] font-bold text-amber-600/70 mt-4 uppercase tracking-widest relative z-10">
                                            Dinilai pada: {{ \Carbon\Carbon::parse($session->feedback_at)->translatedFormat('d M Y, H:i') }}
                                        </p>
                                    </div>
                                @else
                                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 text-center">
                                        <i class="ph-duotone ph-hourglass-high text-4xl text-slate-300 mb-3"></i>
                                        <p class="text-sm font-bold text-slate-600">Menunggu Penilaian</p>
                                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Siswa belum mengisi survei kepuasan layanan.</p>
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
                html: "Jurnal yang disimpan akan bersifat <b class='text-rose-500'>Read-Only (terkunci)</b> sebagai arsip resmi sekolah dan tidak dapat diubah kembali.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981', // emerald-500
                cancelButtonColor: '#94a3b8',  // slate-400
                confirmButtonText: 'Ya, Selesaikan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border border-slate-100 shadow-2xl',
                    confirmButton: 'rounded-xl font-bold px-6 py-3',
                    cancelButton: 'rounded-xl font-bold px-6 py-3'
                }
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
                    customClass: { popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans' }
                });
            @endif
            @if(session('error'))
                Swal.fire({
                    icon: 'error', title: 'Oops...', text: "{!! session('error') !!}",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
                    customClass: { popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans' }
                });
            @endif
        });

        // Chat Handler
        function bkTeacherChatHandler(sessionId) {
            return {
                messages: [], newMessage: '', isSending: false,
                init() { this.fetchMessages(); setInterval(() => this.fetchMessages(), 3000); },
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
                      .catch(err => { this.isSending = false; this.messages.pop(); Swal.fire('Gagal!', 'Tidak dapat mengirim pesan', 'error'); });
                },
                scrollToBottom() { this.$nextTick(() => { let b = this.$refs.chatBox; if(b) b.scrollTop = b.scrollHeight; }); },
                formatTime(iso) { if(!iso) return ''; return new Date(iso).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}); }
            }
        }
    </script>
</x-app-layout>