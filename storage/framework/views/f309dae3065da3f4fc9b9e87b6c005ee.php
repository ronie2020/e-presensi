<?php $__env->startSection('content'); ?>

<?php
    use Carbon\Carbon;
    $startDate = $startDate ?? Carbon::now()->startOfMonth()->format('Y-m-d'); 
    $today = $today ?? Carbon::now()->format('Y-m-d');
    $canFill = $canFill ?? false;
    $todayRamadanLog = $todayRamadanLog ?? null;
    $calendarLogs = $calendarLogs ?? [];
    $totalRamadanDays = $totalRamadanDays ?? 30; // Opsional dari controller
    
    // Ambil data user untuk fallback city
    $userCity = Auth::guard('student')->user()->city ?? 'Jakarta';
?>

<div class="min-h-screen bg-slate-50 pb-20 pt-10 px-4 font-sans" x-data="{ isSaving: false }">
    <div class="max-w-4xl mx-auto">
        
        <!-- HEADER -->
        <div class="mb-6 flex items-center gap-3">
            <a href="<?php echo e(route('portal.show', Auth::guard('student')->id())); ?>?tab=ramadan_jurnal" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition shadow-sm">
                <i class="ph-bold ph-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-lg font-black text-slate-800">Kembali ke Portal</h2>
                <p class="text-xs text-slate-400 font-medium"><?php echo e(\Carbon\Carbon::parse($today)->translatedFormat('l, d F Y')); ?></p>
            </div>
        </div>

        
        <div x-data="prayerWidgetIndex()" x-init="init()" class="relative mb-8">
            <template x-if="isLoading">
                <div class="bg-slate-200 rounded-[2.5rem] p-8 shadow-sm animate-pulse h-48 w-full"></div>
            </template>

            <div x-show="!isLoading" 
                 class="bg-gradient-to-b from-[#0F2027] via-[#203A43] to-[#2C5364] rounded-[2.5rem] p-6 md:p-8 text-white shadow-xl shadow-slate-300 relative overflow-hidden group border-b-4 border-amber-500"
                 style="display: none;">
                <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                <div class="absolute bottom-0 left-0 right-0 h-32 bg-repeat-x opacity-20 pointer-events-none" 
                     style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxNDQwIDMyMCI+PHBhdGggZmlsbD0iI2ZmZmZmZiIgZmlsbC1vcGFjaXR5PSIxIiBkPSJNMCAyMjR4NDggMjEzLjN4OTYgMjAyLjd4MTQ0IDE5MnMxOTIgMzIgMjQwIDMyIDI0MC0zMiAyNDAtMzJzMTkyIDMyIDI0MCAzMiAyNDAtMzIgMjQwLTMyVjMyMEgwWiIvPjwvc3ZnPg=='); background-position: bottom;"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-300 text-[10px] font-bold uppercase tracking-wider mb-2 backdrop-blur-sm cursor-pointer hover:bg-amber-500/30 transition-colors"
                             @click="checkLocation()" title="Refresh Lokasi">
                            <i class="ph-fill ph-map-pin"></i> <span x-text="locationName">...</span>
                            <template x-if="usingGeolocation"><i class="ph-bold ph-crosshair text-[10px] ml-1 text-emerald-300"></i></template>
                        </div>
                        <h3 class="text-3xl md:text-4xl font-serif text-amber-50 tracking-wide mb-1" x-text="nextEventName">...</h3>
                        <p class="text-slate-300 text-xs font-medium font-mono">
                            <i class="ph-bold ph-hourglass-medium text-amber-400"></i> <span x-text="countdown">00:00:00</span>
                        </p>
                    </div>

                    <div class="w-full md:w-auto overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                        <div class="flex md:grid md:grid-cols-6 gap-3 min-w-max px-2">
                            <template x-for="(time, name) in schedule" :key="name">
                                <div class="flex flex-col items-center group/item">
                                    <div class="w-14 h-20 rounded-t-full flex flex-col items-center justify-end pb-2 transition-all duration-300 relative overflow-hidden border-b-2"
                                         :class="currentEvent === name ? 'bg-amber-100 border-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.5)] -translate-y-1' : 'bg-white/5 border-white/10 hover:bg-white/10'">
                                        <span class="text-[9px] uppercase tracking-wider mb-1" 
                                              :class="currentEvent === name ? 'text-amber-800 font-bold' : 'text-slate-300'" x-text="name"></span>
                                        <span class="text-xs font-mono" 
                                              :class="currentEvent === name ? 'text-slate-900 font-black' : 'text-white font-medium'" x-text="time"></span>
                                        <div x-show="currentEvent === name" class="absolute top-2 w-1.5 h-1.5 rounded-full bg-amber-500 shadow-sm animate-pulse"></div>
                                    </div>
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
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6 px-2 border-b border-slate-50 pb-4">
                    <h3 class="font-serif font-bold text-slate-800 flex items-center gap-3 text-lg">
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
                    
                    <?php for($i = 0; $i < $totalRamadanDays; $i++): ?>
                        <?php
                            $dateCheck = \Carbon\Carbon::parse($startDate)->addDays($i);
                            $dateString = $dateCheck->format('Y-m-d');
                            $isToday = $dateString === $today;
                            $isPast = $dateCheck->lt(\Carbon\Carbon::parse($today));
                            $logExists = isset($calendarLogs[$dateString]);
                            
                            $containerClass = "bg-slate-50 border-slate-100 text-slate-400 hover:border-emerald-200 hover:bg-white hover:shadow-md transition-all duration-300";
                            $badge = null;

                            if ($isToday) {
                                $containerClass = "bg-gradient-to-br from-emerald-500 to-teal-600 border-emerald-500 text-white shadow-lg shadow-emerald-500/30 scale-110 ring-4 ring-white z-10";
                            } elseif ($logExists) {
                                $containerClass = "bg-emerald-50 border-emerald-200 text-emerald-700";
                                $badge = '<div class="absolute -top-1.5 -right-1.5 bg-emerald-500 text-white rounded-full p-0.5 shadow-sm border-2 border-white"><i class="ph-bold ph-check text-[10px]"></i></div>';
                            } elseif ($isPast) {
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
            </div>
        </div>

        
        <?php if(!$canFill): ?>
        <div class="bg-amber-50 border border-amber-200 p-6 rounded-[2rem] text-center mb-8">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3"><i class="ph-bold ph-lock-key text-2xl"></i></div>
            <h3 class="font-bold text-amber-800">Waktu Pengisian Ditutup</h3>
            <p class="text-sm text-amber-600 mt-1 max-w-md mx-auto">Formulir ini hanya terbuka selama <b>1x24 jam</b> pada tanggal <?php echo e(\Carbon\Carbon::parse($today)->translatedFormat('d F Y')); ?>.</p>
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
                                
                                <input type="hidden" name="is_fasting" value="0">
                                <input type="checkbox" name="is_fasting" value="1" class="sr-only peer" <?php echo e(($todayRamadanLog->is_fasting ?? true) ? 'checked' : ''); ?>>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-clock text-emerald-500"></i> Shalat Wajib 5 Waktu</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                <?php $__currentLoopData = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $checked = $todayRamadanLog->prayers[$p] ?? false; ?>
                                <label class="cursor-pointer group">
                                    
                                    <input type="hidden" name="prayer_<?php echo e($p); ?>" value="0">
                                    <input type="checkbox" name="prayer_<?php echo e($p); ?>" value="1" class="hidden peer" <?php echo e($checked ? 'checked' : ''); ?>>
                                    
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
                                    
                                    <input type="hidden" name="sunnah_<?php echo e($s); ?>" value="0">
                                    <input type="checkbox" name="sunnah_<?php echo e($s); ?>" value="1" class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500" <?php echo e($checked ? 'checked' : ''); ?>>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <?php if($canFill): ?>
                            <button type="submit" class="w-full mt-10 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 group hover:-translate-y-1" :disabled="isSaving">
                                <template x-if="!isSaving"><div class="flex items-center gap-2"><i class="ph-bold ph-floppy-disk"></i> Simpan Jurnal</div></template>
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
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Notifikasi SweetAlert2 jika ada session success
        <?php if(session('success')): ?>
            Swal.fire({
                title: 'Alhamdulillah!',
                text: "<?php echo session('success'); ?>",
                icon: 'success',
                confirmButtonText: 'Kembali',
                confirmButtonColor: '#10b981', // emerald-500
                background: '#f0fdf4', // emerald-50
                color: '#064e3b', // emerald-900
                iconColor: '#10b981',
                customClass: {
                    popup: 'rounded-[2rem] border-2 border-emerald-100 font-sans',
                    title: 'font-serif text-2xl font-bold'
                }
            });
        <?php endif; ?>
    });

    function prayerWidgetIndex() {
        return {
            isLoading: true,
            usingGeolocation: false,
            city: null, 
            latitude: null,
            longitude: null,
            schedule: {},
            nextEventName: 'Memuat...',
            countdown: '00:00:00',
            locationName: 'Mencari Lokasi...',
            currentEvent: '',
            
            async init() {
                setTimeout(() => { this.checkLocation(); }, 500);
                setInterval(() => this.updateCountdown(), 1000);
            },

            checkLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            this.usingGeolocation = true;
                            this.locationName = "Lokasi Saat Ini"; 
                            this.fetchTimesByCoords();
                        },
                        (error) => { this.useFallbackCity(); }
                    );
                } else {
                    this.useFallbackCity();
                }
            },

            useFallbackCity() {
                this.usingGeolocation = false;
                this.city = '<?php echo e($userCity); ?>'; 
                this.locationName = this.city;
                this.fetchTimesByCity();
            },

            async fetchTimesByCoords() {
                try {
                    const date = new Date();
                    const timestamp = Math.floor(date.getTime() / 1000);
                    const url = `https://api.aladhan.com/v1/timings/${timestamp}?latitude=${this.latitude}&longitude=${this.longitude}&method=20`;
                    
                    const res = await fetch(url);
                    const data = await res.json();
                    this.processData(data);
                } catch (e) { this.useFallbackCity(); }
            },

            async fetchTimesByCity() {
                try {
                    const date = new Date();
                    const url = `https://api.aladhan.com/v1/timingsByCity/${date.getDate()}-${date.getMonth()+1}-${date.getFullYear()}?city=${this.city}&country=Indonesia&method=20`;
                    
                    const res = await fetch(url);
                    const data = await res.json();
                    this.processData(data);
                } catch (e) {
                    this.nextEventName = "Offline";
                    this.isLoading = false;
                }
            },

            processData(data) {
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
                    this.isLoading = false;
                }
            },

            updateCountdown() {
                if(this.isLoading || !this.schedule['Subuh']) return;
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
                    if(nextName === 'Maghrib') this.nextEventName = 'Menuju Berbuka';
                    else if (nextName.includes('Imsak')) this.nextEventName = 'Menuju Imsak';
                    else this.nextEventName = `Menuju ${nextName}`;
                    this.currentEvent = nextName.replace(' (Besok)', '');
                }
            }
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/ramadan/student_index.blade.php ENDPATH**/ ?>