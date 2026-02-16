<?php $__env->startSection('content'); ?>

<?php
    use Carbon\Carbon;
    $startDate = $startDate ?? Carbon::now()->startOfMonth()->format('Y-m-d'); 
    $today = $today ?? Carbon::now()->format('Y-m-d');
    $canFill = $canFill ?? false;
    $todayRamadanLog = $todayRamadanLog ?? null;
    $calendarLogs = $calendarLogs ?? [];
?>

<div class="min-h-screen bg-slate-50 pb-20 pt-10 px-4" x-data="{ isSaving: false }">
    <div class="max-w-4xl mx-auto">
        
        <!-- HEADER -->
        <div class="mb-6 flex items-center gap-3">
            <a href="<?php echo e(route('portal.show', Auth::guard('student')->id())); ?>"class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition shadow-sm">
                <i class="ph-bold ph-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-lg font-black text-slate-800">Kembali ke Portal</h2>
                <p class="text-xs text-slate-400 font-medium"><?php echo e(\Carbon\Carbon::parse($today)->isoFormat('dddd, D MMMM Y')); ?></p>
            </div>
        </div>

        
        <div x-data="prayerWidget()" x-init="init()" class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-[2.5rem] p-6 md:p-8 text-white shadow-xl shadow-emerald-900/20 mb-8 relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black/10 rounded-full blur-2xl"></div>
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    
                    <div class="text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 border border-white/20 text-[10px] font-bold uppercase tracking-wider mb-2">
                            <i class="ph-fill ph-map-pin"></i> <span x-text="locationName">Memuat Lokasi...</span>
                        </div>
                        <h3 class="text-3xl md:text-4xl font-black tracking-tight mb-1" x-text="nextEventName">...</h3>
                        <p class="text-emerald-100 text-sm font-medium opacity-90">
                            <span x-text="countdown">00:00:00</span> Menuju Waktu Berikutnya
                        </p>
                    </div>

                    
                    <div class="w-full md:w-auto">
                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                            <template x-for="(time, name) in schedule" :key="name">
                                <div class="flex flex-col items-center justify-center p-3 rounded-2xl border transition-all"
                                    :class="currentEvent === name ? 'bg-white text-emerald-700 border-white shadow-lg scale-105 font-bold' : 'bg-white/10 text-white border-white/10'">
                                    <span class="text-[9px] uppercase tracking-wider opacity-80 mb-1" x-text="name"></span>
                                    <span class="text-sm" :class="currentEvent === name ? 'font-black' : 'font-medium'" x-text="time"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-[2.5rem] p-6 md:p-8 shadow-lg shadow-slate-200/50 border border-slate-100 mb-8 relative overflow-hidden group">
            
            
            <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] pointer-events-none"></div>
            
            
            <div class="absolute -top-10 -right-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="absolute top-6 right-8 text-emerald-900/5 transform rotate-12 pointer-events-none">
                <i class="ph-fill ph-mosque text-[10rem]"></i>
            </div>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6 px-2 border-b border-slate-50 pb-4">
                    <h3 class="font-black text-slate-800 flex items-center gap-3 text-lg">
                        <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-sm">
                            <i class="ph-fill ph-calendar-star text-xl"></i>
                        </span>
                        Kalender Ramadhan
                    </h3>
                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full uppercase tracking-wider border border-emerald-100 flex items-center gap-2">
                        <i class="ph-bold ph-star text-amber-400"></i>
                        Mulai: <?php echo e(\Carbon\Carbon::parse($startDate)->isoFormat('D MMMM')); ?>

                    </span>
                </div>

                <div class="grid grid-cols-7 sm:grid-cols-8 md:grid-cols-10 gap-2 sm:gap-3">
                    <?php for($i = 0; $i < 30; $i++): ?>
                        <?php
                            $dateCheck = \Carbon\Carbon::parse($startDate)->addDays($i);
                            $dateString = $dateCheck->format('Y-m-d');
                            $isToday = $dateString === $today;
                            $isPast = $dateCheck->lt(\Carbon\Carbon::parse($today));
                            $logExists = isset($calendarLogs[$dateString]);
                            
                            // Styling Logic
                            $containerClass = "bg-slate-50 border-slate-100 text-slate-400 hover:border-emerald-200 hover:bg-white hover:shadow-md transition-all duration-300";
                            $badge = null;

                            if ($isToday) {
                                // Hari Ini (Highlight Emerald & Gold)
                                $containerClass = "bg-gradient-to-br from-emerald-500 to-teal-600 border-emerald-500 text-white shadow-lg shadow-emerald-500/30 scale-110 ring-4 ring-white z-10";
                            } elseif ($logExists) {
                                // Sudah Diisi (Hijau Lembut)
                                $containerClass = "bg-emerald-50 border-emerald-200 text-emerald-700";
                                $badge = '<div class="absolute -top-1.5 -right-1.5 bg-emerald-500 text-white rounded-full p-0.5 shadow-sm border-2 border-white"><i class="ph-bold ph-check text-[10px]"></i></div>';
                            } elseif ($isPast) {
                                // Terlewat & Kosong (Merah Pudar)
                                $containerClass = "bg-rose-50/50 border-rose-100 text-rose-300 opacity-80";
                                $badge = '<div class="absolute -top-1.5 -right-1.5 bg-white text-rose-300 rounded-full p-0.5 shadow-sm border border-rose-100"><i class="ph-bold ph-x text-[10px]"></i></div>';
                            }
                        ?>
                        
                        <div class="aspect-square rounded-2xl border flex flex-col items-center justify-center relative group cursor-default <?php echo e($containerClass); ?>">
                            <span class="text-[8px] font-bold uppercase mb-0.5 opacity-80 tracking-wider">H-<?php echo e($i + 1); ?></span>
                            <span class="text-sm font-black"><?php echo e($dateCheck->format('d')); ?></span>
                            
                            <?php echo $badge; ?>

                        </div>
                    <?php endfor; ?>
                </div>
                
                
                <div class="flex flex-wrap gap-4 mt-6 px-2 justify-center sm:justify-start">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 border-2 border-white shadow-sm"></span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Hari Ini</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-emerald-100 border border-emerald-200"></span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Tuntas</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-rose-50 border border-rose-100"></span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Kosong</span>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if(!$canFill): ?>
        <div class="bg-amber-50 border border-amber-200 p-6 rounded-[2rem] text-center mb-8">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3"><i class="ph-bold ph-lock-key text-2xl"></i></div>
            <h3 class="font-bold text-amber-800">Waktu Pengisian Ditutup</h3>
            <p class="text-sm text-amber-600 mt-1 max-w-md mx-auto">Formulir ini hanya terbuka selama <b>1x24 jam</b> pada tanggal <?php echo e(\Carbon\Carbon::parse($today)->format('d F Y')); ?>.</p>
        </div>
        <?php endif; ?>
        
        <?php if($todayRamadanLog): ?>
        <div class="bg-emerald-50 border border-emerald-200 p-6 rounded-[2rem] text-center mb-8">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3"><i class="ph-fill ph-check-fat text-2xl"></i></div>
            <h3 class="font-bold text-emerald-800">Alhamdulillah!</h3>
            <p class="text-sm text-emerald-600 mt-1">Kamu sudah mengisi jurnal hari ini. Data tersimpan aman.</p>
        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('student.ramadan.save')); ?>" method="POST" @submit="isSaving = true">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="date" value="<?php echo e($today); ?>">

            <fieldset <?php echo e(!$canFill ? 'disabled' : ''); ?> class="contents group-disabled:opacity-50 group-disabled:pointer-events-none">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    
                    <div class="md:col-span-2 space-y-6">
                        
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-check-circle text-2xl"></i></div>
                                <div><h3 class="font-bold text-slate-800">Status Puasa</h3><p class="text-xs text-slate-400">Apakah kamu berpuasa hari ini?</p></div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_fasting" class="sr-only peer" <?php echo e(($todayRamadanLog->is_fasting ?? true) ? 'checked' : ''); ?>>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-clock text-emerald-500"></i> Shalat Wajib 5 Waktu</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                <?php $__currentLoopData = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $checked = $todayRamadanLog->prayers[$p] ?? false; ?>
                                <label class="cursor-pointer group">
                                    <input type="checkbox" name="prayer_<?php echo e($p); ?>" class="hidden peer" <?php echo e($checked ? 'checked' : ''); ?>>
                                    <div class="p-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-slate-400 transition-all peer-checked:bg-emerald-50 peer-checked:border-emerald-200 peer-checked:text-emerald-700 flex flex-col items-center gap-2">
                                        <span class="text-[10px] font-bold uppercase"><?php echo e($p); ?></span>
                                        <i class="ph-bold ph-check-circle text-xl"></i>
                                    </div>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        
                        <?php if(\Carbon\Carbon::parse($today)->isFriday()): ?>
                        <div class="bg-white p-8 rounded-[2rem] border border-emerald-100 shadow-sm relative overflow-hidden ring-1 ring-emerald-50">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2 relative z-10"><i class="ph-fill ph-mosque text-emerald-600"></i> Laporan Shalat Jumat</h3>
                            <div class="grid grid-cols-1 gap-5 relative z-10">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Khotib</label>
                                    <input type="text" name="friday_khotib" value="<?php echo e($todayRamadanLog->friday_khotib ?? ''); ?>" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-emerald-500 pl-4" placeholder="Nama Ustadz..." <?php echo e(($todayRamadanLog->teacher_verified_at ?? false) ? 'readonly' : ''); ?>>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ringkasan</label>
                                    <textarea name="friday_summary" rows="4" class="w-full bg-slate-50 border-none rounded-xl text-sm font-medium focus:ring-emerald-500" placeholder="Ringkasan khutbah..." <?php echo e(($todayRamadanLog->teacher_verified_at ?? false) ? 'readonly' : ''); ?>><?php echo e($todayRamadanLog->friday_summary ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-book-open text-blue-500"></i> Tilawah & Murojaah</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase">Surah Tadarus</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="tadarus_surah" value="<?php echo e($todayRamadanLog->tadarus_surah ?? ''); ?>" class="flex-1 bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Surah">
                                        <input type="number" name="tadarus_ayah" value="<?php echo e($todayRamadanLog->tadarus_ayah ?? ''); ?>" class="w-20 bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Ayat">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase">Murojaah</label>
                                    <input type="text" name="murojaah_surah" value="<?php echo e($todayRamadanLog->murojaah_surah ?? ''); ?>" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Contoh: An-Naba">
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="space-y-6">
                        
                        
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-microphone-stage text-purple-500"></i> Laporan Kultum
                            </h3>
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Penceramah</label>
                                    <input type="text" name="kultum_penceramah" 
                                        value="<?php echo e($todayRamadanLog->kultum_penceramah ?? ''); ?>" 
                                        class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-purple-500 text-slate-700 placeholder-slate-300" 
                                        placeholder="Nama Penceramah...">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Ringkasan Materi</label>
                                    <textarea name="kultum_summary" rows="4" 
                                        class="w-full bg-slate-50 border-none rounded-xl text-sm font-medium focus:ring-purple-500 text-slate-600 placeholder-slate-300 leading-relaxed resize-none" 
                                        placeholder="Apa isi ceramahnya?"><?php echo e($todayRamadanLog->kultum_summary ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-star text-amber-500"></i> Amalan Sunnah</h3>
                            <div class="space-y-3 flex-1">
                                <?php $__currentLoopData = ['tarawih', 'witir', 'dhuha', 'rawatib', 'sedekah']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $checked = $todayRamadanLog->sunnah_deeds[$s] ?? false; ?>
                                <label class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-50 cursor-pointer hover:border-emerald-200 transition-all">
                                    <span class="text-sm font-bold text-slate-600 capitalize"><?php echo e($s); ?></span>
                                    <input type="checkbox" name="sunnah_<?php echo e($s); ?>" class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500" <?php echo e($checked ? 'checked' : ''); ?>>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <?php if($canFill): ?>
                            <button type="submit" class="w-full mt-10 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2 group" :disabled="isSaving">
                                <template x-if="!isSaving"><div class="flex items-center gap-2 group-hover:scale-105 transition-transform"><i class="ph-bold ph-floppy-disk"></i> Simpan Jurnal</div></template>
                                <template x-if="isSaving"><div class="flex items-center gap-2"><i class="ph-bold ph-spinner animate-spin"></i> Memproses...</div></template>
                            </button>
                            <?php else: ?>
                             <div class="w-full mt-10 bg-slate-200 text-slate-400 font-bold py-4 rounded-2xl text-center cursor-not-allowed">
                                <i class="ph-bold ph-lock-key"></i> Form Terkunci
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>


<script>
    function prayerWidget() {
        return {
            city: 'Ciamis', // Ganti sesuai lokasi sekolah
            country: 'Indonesia',
            
            schedule: {},
            nextEventName: 'Memuat...',
            countdown: '00:00:00',
            locationName: 'Memuat...',
            currentEvent: '',
            
            async init() {
                this.locationName = `${this.city}, ${this.country}`;
                await this.fetchTimes();
                
                // Update countdown setiap detik
                setInterval(() => {
                    this.updateCountdown();
                }, 1000);
            },

            async fetchTimes() {
                try {
                    const date = new Date();
                    const url = `https://api.aladhan.com/v1/timingsByCity/${date.getDate()}-${date.getMonth()+1}-${date.getFullYear()}?city=${this.city}&country=${this.country}&method=20`; 
                    
                    const res = await fetch(url);
                    const data = await res.json();
                    
                    if(data.code === 200) {
                        const timings = data.data.timings;
                        this.schedule = {
                            'Imsak': timings.Imsak,
                            'Subuh': timings.Fajr,
                            'Dzuhur': timings.Dhuhr,
                            'Ashar': timings.Asr,
                            'Maghrib': timings.Maghrib,
                            'Isya': timings.Isha
                        };
                        this.updateCountdown();
                    }
                } catch (e) {
                    console.error("Gagal mengambil jadwal shalat", e);
                    this.nextEventName = "Offline";
                }
            },

            updateCountdown() {
                const now = new Date();
                let nextTime = null;
                let nextName = '';
                let minDiff = Infinity;

                for (const [name, timeStr] of Object.entries(this.schedule)) {
                    const [hours, minutes] = timeStr.split(':');
                    const timeDate = new Date();
                    timeDate.setHours(hours, minutes, 0);

                    if (timeDate < now) continue;

                    const diff = timeDate - now;
                    if (diff < minDiff) {
                        minDiff = diff;
                        nextTime = timeDate;
                        nextName = name;
                    }
                }

                if (!nextTime && this.schedule['Imsak']) {
                    const [hours, minutes] = this.schedule['Imsak'].split(':');
                    nextTime = new Date();
                    nextTime.setDate(nextTime.getDate() + 1);
                    nextTime.setHours(hours, minutes, 0);
                    nextName = 'Imsak (Besok)';
                    minDiff = nextTime - now;
                }

                if (nextTime) {
                    const h = Math.floor(minDiff / (1000 * 60 * 60));
                    const m = Math.floor((minDiff % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((minDiff % (1000 * 60)) / 1000);
                    
                    this.countdown = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                    
                    if(nextName === 'Maghrib') {
                        this.nextEventName = 'Menuju Berbuka';
                    } else if (nextName === 'Imsak' || nextName === 'Imsak (Besok)') {
                        this.nextEventName = 'Menuju Imsak';
                    } else {
                        this.nextEventName = `Menuju ${nextName}`;
                    }
                    
                    this.currentEvent = nextName.replace(' (Besok)', '');
                }
            }
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ramadan/student_index.blade.php ENDPATH**/ ?>