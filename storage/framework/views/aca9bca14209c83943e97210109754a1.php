<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    

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
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        
        
        
        <div class="hidden print:block w-full border-b-4 border-double border-slate-800 pb-4 mb-8 text-center">
            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-600 mb-1">Pemerintah Provinsi Daerah</h3>
            <h1 class="text-2xl font-black uppercase tracking-wider text-slate-900 mb-1">Nama Sekolah Anda</h1>
            <p class="text-xs font-medium text-slate-700">Jl. Contoh Alamat Sekolah No. 123, Kota/Kabupaten, Kode Pos 12345</p>
            <p class="text-xs font-medium text-slate-700">Telp: (0123) 456789 | Email: info@sekolahanda.sch.id | Web: sekolahanda.sch.id</p>
            <h2 class="text-lg font-bold uppercase tracking-widest text-slate-800 mt-6 underline decoration-2 underline-offset-4">Dokumen Jurnal Bimbingan Konseling</h2>
            <p class="text-xs font-bold text-slate-500 mt-2">No. Referensi: BK-<?php echo e(date('Y')); ?>-<?php echo e(str_pad($session->id, 4, '0', STR_PAD_LEFT)); ?></p>
        </div>

        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 print:hidden">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm font-bold text-blue-600 mb-1 uppercase tracking-wider">
                        <i class="ph-fill ph-hash"></i> Sesi Konseling <?php echo e($session->id); ?>

                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Proses & Tindak Lanjut</h1>
                    <p class="text-slate-500 font-medium">Kelola status pengajuan dan rekam hasil konseling siswa.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    
                    <?php if($session->status == 'finished'): ?>
                        <button onclick="window.print()" class="group flex items-center gap-2 px-5 py-2.5 bg-indigo-50 border border-indigo-200 rounded-2xl text-indigo-700 font-bold hover:bg-indigo-600 hover:text-white shadow-sm hover:shadow-md transition-all">
                            <i class="ph-bold ph-printer group-hover:animate-bounce"></i>
                            Cetak Jurnal
                        </button>
                    <?php endif; ?>

                    <a href="<?php echo e(route('admin.bk.index')); ?>" class="group flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-slate-600 font-bold hover:border-blue-400 hover:text-blue-600 shadow-sm hover:shadow-md transition-all">
                        <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        Kembali
                    </a>
                </div>
            </div>

            
            <div class="mt-8 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between relative overflow-hidden">
                <div class="absolute top-1/2 left-8 right-8 h-1 bg-slate-100 -translate-y-1/2 rounded-full z-0"></div>
                
                <?php
                    $isApproved = in_array($session->status, ['approved', 'finished', 'ongoing']);
                    $isFinished = $session->status == 'finished';
                    $isRejected = $session->status == 'rejected';
                ?>

                <!-- Step 1: Pengajuan -->
                <div class="relative z-10 flex flex-col items-center bg-white px-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white shadow-md bg-blue-500 ring-4 ring-white">
                        <i class="ph-bold ph-check"></i>
                    </div>
                    <div class="text-[10px] font-bold text-slate-800 uppercase mt-2">Pengajuan</div>
                </div>

                <!-- Step 2: Respon -->
                <?php if($isRejected): ?>
                    <div class="relative z-10 flex flex-col items-center bg-white px-4">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white shadow-md bg-rose-500 ring-4 ring-white">
                            <i class="ph-bold ph-x"></i>
                        </div>
                        <div class="text-[10px] font-bold text-rose-600 uppercase mt-2">Ditolak</div>
                    </div>
                <?php else: ?>
                    <div class="relative z-10 flex flex-col items-center bg-white px-4">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white shadow-md ring-4 ring-white <?php echo e($isApproved ? 'bg-blue-500' : 'bg-slate-200 text-slate-400'); ?>">
                            <?php if($isApproved): ?> <i class="ph-bold ph-check"></i> <?php else: ?> <span class="text-xs">2</span> <?php endif; ?>
                        </div>
                        <div class="text-[10px] font-bold uppercase mt-2 <?php echo e($isApproved ? 'text-slate-800' : 'text-slate-400'); ?>">Tanggapan BK</div>
                    </div>
                <?php endif; ?>

                <!-- Step 3: Selesai -->
                <?php if(!$isRejected): ?>
                    <div class="relative z-10 flex flex-col items-center bg-white px-4">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white shadow-md ring-4 ring-white <?php echo e($isFinished ? 'bg-emerald-500' : 'bg-slate-200 text-slate-400'); ?>">
                            <?php if($isFinished): ?> <i class="ph-bold ph-check-circle"></i> <?php else: ?> <span class="text-xs">3</span> <?php endif; ?>
                        </div>
                        <div class="text-[10px] font-bold uppercase mt-2 <?php echo e($isFinished ? 'text-emerald-600' : 'text-slate-400'); ?>">Selesai / Arsip</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM KIRI: INFO SISWA & MASALAH -->
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
                                    <?php if($session->student && $session->student->photo_path): ?>
                                        <img class="w-full h-full rounded-full object-cover" src="<?php echo e(asset('storage/' . $session->student->photo_path)); ?>" alt="Foto Siswa">
                                    <?php else: ?>
                                        <div class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-3xl font-black">
                                            <?php echo e(substr($session->student->name ?? 'X', 0, 1)); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="font-black text-xl text-slate-800 leading-tight"><?php echo e($session->student->name ?? 'Siswa Terhapus'); ?></div>
                            <div class="text-sm font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full mt-2 border border-blue-100">
                                <?php echo e($session->student->schoolClass->name ?? 'Tanpa Kelas'); ?>

                            </div>
                        </div>

                        <div class="space-y-4 relative z-10">
                            <div class="flex justify-between items-center p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                <span class="text-xs font-bold text-slate-400 uppercase">NIS / NISN</span>
                                <span class="font-bold text-slate-700 font-mono"><?php echo e($session->student->nis ?? '-'); ?> / <?php echo e($session->student->student_id ?? '-'); ?></span>
                            </div>
                            
                            <div class="print:hidden">
                                <?php if($session->student->parent_wa_number ?? false): ?>
                                    <?php
                                        $waMessage = "Salam hormat Bapak/Ibu Orang Tua/Wali dari " . ($session->student->name ?? '') . ",\n\nKami dari pihak Bimbingan Konseling sekolah ingin berdiskusi terkait ananda. Mohon konfirmasi ketersediaan Bapak/Ibu untuk komunikasi lebih lanjut. Terima kasih.";
                                        $waLink = "https://wa.me/" . preg_replace('/^0/', '62', $session->student->parent_wa_number) . "?text=" . urlencode($waMessage);
                                    ?>
                                    <a href="<?php echo e($waLink); ?>" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-emerald-50 text-emerald-600 font-bold rounded-xl border border-emerald-100 hover:bg-emerald-100 transition-colors">
                                        <i class="ph-fill ph-whatsapp-logo text-xl"></i> 
                                        Hubungi Orang Tua
                                    </a>
                                <?php else: ?>
                                    <div class="flex items-center justify-center gap-2 w-full py-3 bg-slate-50 text-slate-400 font-bold rounded-xl border border-slate-100 cursor-not-allowed">
                                        <i class="ph-slash ph-whatsapp-logo text-xl"></i> No. WA Tidak Ada
                                    </div>
                                <?php endif; ?>

                                <?php if(Route::has('admin.discipline.student_history')): ?>
                                    <a href="<?php echo e(route('admin.discipline.student_history', $session->student->id)); ?>" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-indigo-50 text-indigo-600 font-bold rounded-xl border border-indigo-100 hover:bg-indigo-100 transition-colors mt-2">
                                        <i class="ph-bold ph-shield-warning text-xl"></i> 
                                        Lihat Rekam Disiplin
                                    </a>
                                <?php endif; ?>
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
                                <i class="ph-bold ph-tag mr-1"></i> <?php echo e($session->category->name ?? 'Umum'); ?>

                            </span>
                            <span class="px-3 py-1.5 text-xs rounded-lg bg-slate-100 text-slate-600 border border-slate-200 font-bold uppercase tracking-wide">
                                <?php if($session->method == 'online'): ?>
                                    <i class="ph-bold ph-globe mr-1"></i> Online
                                <?php else: ?>
                                    <i class="ph-bold ph-users mr-1"></i> Tatap Muka
                                <?php endif; ?>
                            </span>
                        </div>

                        
                        <?php if($session->is_system_generated): ?>
                            <?php if(str_contains($session->initial_message, 'PELANGGARAN')): ?>
                                <div class="mb-4 bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start gap-3">
                                    <div class="p-2 bg-rose-100 text-rose-600 rounded-lg animate-pulse print:hidden">
                                        <i class="ph-fill ph-warning-octagon text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-rose-800 uppercase tracking-wider mb-1">Panggilan Otomatis Sistem</h4>
                                        <p class="text-xs text-rose-700 font-medium">Tiket ini dibuat secara otomatis karena siswa telah mencapai ambang batas poin pelanggaran di modul Disiplin.</p>
                                    </div>
                                </div>
                            <?php elseif(str_contains($session->initial_message, 'PRESTASI')): ?>
                                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg print:hidden">
                                        <i class="ph-fill ph-medal text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-blue-800 uppercase tracking-wider mb-1">Apresiasi Sistem Otomatis</h4>
                                        <p class="text-xs text-blue-700 font-medium">Siswa mencapai poin kebaikan luar biasa. Tiket ini dibuat untuk pemberian apresiasi / bimbingan lanjutan.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div class="relative mb-2">
                            <div class="absolute -top-3 -left-2 text-5xl text-blue-100 font-serif opacity-50 print:hidden">“</div>
                            <div class="relative z-10 bg-blue-50/50 p-5 rounded-2xl border border-blue-100 text-slate-700 italic font-medium leading-relaxed">
                                <?php echo nl2br(e($session->initial_message)); ?>

                            </div>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-end gap-1.5 text-xs font-bold text-slate-400">
                            <i class="ph-bold ph-clock"></i> 
                            Diajukan: <?php echo e($session->created_at->translatedFormat('d M Y, H:i')); ?>

                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: AKSI & JURNAL -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- 1. FORM APPROVAL & TEMPLATE WA (Action Card) -->
                    <?php if($session->status == 'pending'): ?>
                    <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-amber-500/10 border border-amber-100 relative overflow-hidden print:hidden" 
                         x-data="{ 
                            action: 'approved',
                            responseMsg: '',
                            studentName: '<?php echo e(addslashes($session->student->name ?? 'Siswa')); ?>',
                            
                            setTemplate(type) {
                                if(type === 'panggilan') {
                                    this.responseMsg = `Yth. Bapak/Ibu Orang Tua/Wali dari ${this.studentName},\n\nKami mengundang kehadiran Bapak/Ibu ke sekolah (Ruang BK) pada jadwal yang telah kami tentukan untuk mendiskusikan laporan evaluasi kedisiplinan ananda.\n\nAtas perhatian dan kehadirannya kami ucapkan terima kasih.`;
                                } else if(type === 'apresiasi') {
                                    this.responseMsg = `Yth. Bapak/Ibu Orang Tua/Wali dari ${this.studentName},\n\nKami ingin menyampaikan apresiasi dari pihak sekolah terkait pencapaian positif ananda baru-baru ini. Mari kita terus dukung ananda agar semakin berprestasi!\n\nSalam hangat dari sekolah.`;
                                } else if(type === 'teguran') {
                                    this.responseMsg = `Yth. Bapak/Ibu Orang Tua/Wali dari ${this.studentName},\n\nMelalui pesan ini kami ingin menginformasikan evaluasi kedisiplinan ananda di sekolah. Mohon kerja samanya di rumah untuk memberikan arahan dan bimbingan.\n\nTerima kasih.`;
                                } else if(type === 'umum') {
                                    this.responseMsg = `Halo ${this.studentName},\n\nPengajuan konseling kamu telah kami terima dan disetujui. Silakan datang ke ruang BK tepat waktu sesuai jadwal yang dilampirkan ya. Semangat!`;
                                } else {
                                    this.responseMsg = '';
                                }
                            },
                            
                            // Fitur Copy to Clipboard
                            copyToClipboard() {
                                if (!this.responseMsg) return;
                                navigator.clipboard.writeText(this.responseMsg).then(() => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Teks Disalin!',
                                        text: 'Template siap di-paste ke WhatsApp.',
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        customClass: { popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans' }
                                    });
                                }).catch(err => {
                                    console.error('Gagal menyalin: ', err);
                                });
                            }
                         }">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                        
                        <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                            <div class="p-2 bg-amber-100 rounded-lg text-amber-600">
                                <i class="ph-fill ph-gavel text-xl"></i>
                            </div>
                            Tindakan Guru BK
                        </h3>

                        <form action="<?php echo e(route('admin.bk.update_status', $session->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keputusan</label>
                                    <div class="relative">
                                        <select name="status" x-model="action" class="w-full pl-4 pr-10 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all appearance-none cursor-pointer shadow-sm">
                                            <option value="approved">Setujui & Jadwalkan Pertemuan</option>
                                            <option value="finished">Pemberitahuan Langsung (Selesai)</option>
                                            <option value="rejected">Tolak Pengajuan</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500">
                                            <i class="ph-bold ph-caret-down"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Field Jadwal (Hanya muncul jika disetujui / dijadwalkan) -->
                                <div x-show="action === 'approved'" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Jadwal Pertemuan</label>
                                    <input type="datetime-local" name="scheduled_at" 
                                           min="<?php echo e(now()->format('Y-m-d\TH:i')); ?>"
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

                                <!-- Template Buttons dinamis tergantung tipe keputusan -->
                                <div x-show="action !== 'rejected'" class="flex flex-wrap gap-2 mb-3" x-transition>
                                    
                                    <!-- Muncul jika akan dijadwalkan (Approved) -->
                                    <button type="button" @click="setTemplate('panggilan')" x-show="action === 'approved'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 transition-colors shadow-sm">
                                        <i class="ph-bold ph-warning"></i> Panggilan Ortu
                                    </button>
                                    <button type="button" @click="setTemplate('umum')" x-show="action === 'approved'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200 transition-colors shadow-sm">
                                        <i class="ph-bold ph-chat-text"></i> Info ke Siswa
                                    </button>

                                    <!-- Muncul jika hanya pemberitahuan langsung (Finished) -->
                                    <button type="button" @click="setTemplate('apresiasi')" x-show="action === 'finished'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 transition-colors shadow-sm">
                                        <i class="ph-bold ph-medal"></i> Apresiasi Prestasi
                                    </button>
                                    <button type="button" @click="setTemplate('teguran')" x-show="action === 'finished'" class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100 transition-colors shadow-sm">
                                        <i class="ph-bold ph-warning"></i> Teguran Ringan
                                    </button>

                                    <!-- Hapus Teks -->
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
                    <?php endif; ?>

                    <!-- BLOK STATUS: REJECTED -->
                    <?php if($session->status == 'rejected'): ?>
                    <div class="bg-rose-50 rounded-[2rem] p-6 border border-rose-100 flex flex-col md:flex-row items-start gap-4 break-inside-avoid">
                        <div class="p-3 bg-white rounded-2xl text-rose-500 shadow-sm shrink-0 print:hidden">
                            <i class="ph-duotone ph-x-circle text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-rose-800">Pengajuan Ditolak</h3>
                            <p class="text-rose-700/80 font-medium mt-1 text-sm leading-relaxed">
                                "<?php echo e($session->response_message); ?>"
                            </p>
                            <div class="mt-3 text-xs font-bold text-rose-400 flex items-center gap-1">
                                <i class="ph-bold ph-clock"></i> Diproses pada: <?php echo e($session->updated_at->translatedFormat('d M Y, H:i')); ?>

                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- BLOK STATUS: JADWAL (Approved/Finished) -->
                    <?php if($session->status == 'approved' || $session->status == 'finished'): ?>
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/60 border border-slate-100 relative overflow-hidden break-inside-avoid">
                         <div class="absolute top-0 left-0 w-1 h-full bg-blue-500 print:hidden"></div>
                         
                        <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                            <div>
                                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                    <i class="ph-duotone ph-calendar-check text-blue-500 text-2xl print:hidden"></i> 
                                    <?php echo e($session->scheduled_at ? 'Sesi Terjadwal' : 'Pemberitahuan Selesai'); ?>

                                </h3>
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 print:border print:border-blue-200">
                                            <i class="ph-bold ph-clock"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-400 uppercase">Waktu Pertemuan</div>
                                            <div class="font-bold text-slate-800">
                                                <?php if($session->scheduled_at): ?>
                                                    <?php echo e($session->scheduled_at->translatedFormat('l, d F Y - H:i')); ?> WIB
                                                <?php else: ?>
                                                    <span class="text-emerald-600">Pemberitahuan Langsung (Tanpa Tatap Muka)</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 pt-2">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0 print:border print:border-indigo-200">
                                            <i class="ph-bold ph-chat-centered-text"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-400 uppercase">Pesan & Informasi</div>
                                            <div class="font-medium text-slate-600 text-sm italic whitespace-pre-line leading-relaxed">"<?php echo e($session->response_message); ?>"</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if($session->status == 'approved'): ?>
                                <div class="px-4 py-2 bg-blue-100 text-blue-700 rounded-xl font-bold text-xs flex items-center gap-2 animate-pulse print:hidden">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Sedang Berlangsung
                                </div>
                            <?php else: ?>
                                <div class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl font-bold text-xs flex items-center gap-2 print:border print:border-emerald-200">
                                    <i class="ph-fill ph-check-circle"></i> Selesai
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- 2. FORM JURNAL (Main Content) -->
                    <?php if($session->status == 'approved' || $session->status == 'finished'): ?>
                    <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/60 border border-slate-100 mt-6 break-inside-avoid">
                        <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-4">
                            <h3 class="text-xl font-black text-slate-800 flex items-center gap-3">
                                <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-600 print:hidden">
                                    <i class="ph-fill ph-notebook text-xl"></i>
                                </div>
                                Jurnal Konseling
                            </h3>
                            <?php if($session->status == 'finished'): ?>
                                <span class="text-xs bg-slate-100 text-slate-500 px-3 py-1.5 rounded-lg border border-slate-200 font-bold flex items-center gap-1.5 uppercase tracking-wide">
                                    <i class="ph-fill ph-lock-key"></i> Read Only
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Form Input Jurnal -->
                        <?php if($session->status == 'approved'): ?>
                        
                        
                        <form id="jurnalForm" action="<?php echo e(route('admin.bk.store_record', $session->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Analisis Masalah</label>
                                    <textarea name="problem_analysis" rows="3" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm resize-none overflow-hidden" placeholder="Jelaskan akar permasalahan siswa secara detail..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Solusi / Tindakan</label>
                                    <textarea name="solution" rows="3" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm resize-none overflow-hidden" placeholder="Nasihat, perlakuan, atau tindakan yang diberikan..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Hasil Akhir (Follow Up)</label>
                                    <textarea name="result" rows="2" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-medium text-slate-700 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm resize-none overflow-hidden" placeholder="Kesepakatan bersama atau rencana tindak lanjut..."></textarea>
                                </div>
                                
                                <label class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 cursor-pointer transition-colors group print:hidden">
                                    <input type="checkbox" name="is_confidential" value="1" checked class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-colors">
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
                            </div>
                        </form>
                        <?php else: ?>
                            <!-- TAMPILAN READ ONLY (Style Dokumen) -->
                            <div class="space-y-6">
                                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Analisis Permasalahan / Pencapaian</h4>
                                    <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-line"><?php echo e($session->record->problem_analysis ?? '-'); ?></div>
                                </div>
                                
                                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Solusi / Tindakan Lanjutan</h4>
                                    <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-line"><?php echo e($session->record->solution ?? '-'); ?></div>
                                </div>

                                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Hasil Akhir</h4>
                                    <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-line"><?php echo e($session->record->result ?? '-'); ?></div>
                                </div>

                                <?php if($session->record && $session->record->is_confidential): ?>
                                    <div class="flex items-center gap-2 px-4 py-3 bg-rose-50 text-rose-700 rounded-xl font-bold text-xs border border-rose-100 w-fit print:hidden">
                                        <i class="ph-fill ph-lock-key"></i> Dokumen Rahasia (Confidential)
                                    </div>
                                    
                                    
                                    <div class="hidden print:block mt-8 text-center text-rose-700 font-bold uppercase text-2xl border-4 border-rose-700 px-4 py-2 w-max mx-auto opacity-50 rotate-[-15deg]">
                                        CONFIDENTIAL / RAHASIA
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
            
            
            <div class="hidden print:flex justify-between items-end mt-16 px-8 break-inside-avoid">
                <div class="text-center">
                    <p class="text-sm font-medium mb-16">Mengetahui,<br>Kepala Sekolah</p>
                    <p class="text-sm font-bold underline decoration-1 underline-offset-2">_________________________</p>
                    <p class="text-xs mt-1">NIP. ..............................</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium mb-16">Kota/Kabupaten, <?php echo e(now()->translatedFormat('d F Y')); ?><br>Guru Bimbingan Konseling</p>
                    <p class="text-sm font-bold underline decoration-1 underline-offset-2"><?php echo e(Auth::user()->name ?? '_________________________'); ?></p>
                    <p class="text-xs mt-1">NIP. ..............................</p>
                </div>
            </div>

        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Logika Pop-Up Konfirmasi Jurnal
        function confirmJurnal() {
            Swal.fire({
                title: 'Selesaikan Sesi?',
                html: "Jurnal yang disimpan akan bersifat <b class='text-rose-500'>Read-Only (terkunci)</b> sebagai arsip resmi sekolah dan tidak dapat diubah kembali.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981', // Warna emerald-500
                cancelButtonColor: '#94a3b8',  // Warna slate-400
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
                    // Submit form jika menekan 'Ya'
                    document.getElementById('jurnalForm').submit();
                }
            });
        }

        // Logika Toast Global jika ada Flash Message
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo session('success'); ?>",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans'
                    }
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "<?php echo session('error'); ?>",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans'
                    }
                });
            <?php endif; ?>
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\admin\bk\show.blade.php ENDPATH**/ ?>