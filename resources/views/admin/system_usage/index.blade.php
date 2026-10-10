<x-app-layout>
    {{-- CUSTOM STYLES --}}
    <style>
        .fluent-card {
            background: rgba(3, 29, 61, 0.75) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
        .fluent-card:hover {
            border-color: rgba(86, 187, 241, 0.35) !important;
        }
    </style>

    <div class="space-y-6 pb-12">
        <!-- HEADER & FILTER TOOLBAR -->
        <div class="fluent-card p-6 rounded-3xl relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-elevate-accent/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 relative z-10">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-elevate-accent to-elevate-primary flex items-center justify-center text-white shadow-lg shadow-elevate-accent/25">
                            <i class="ph-duotone ph-chart-line-up text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl lg:text-2xl font-black text-white tracking-tight flex items-center gap-2">
                                Rekap Penggunaan Aplikasi
                                <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold uppercase tracking-wider">
                                    Live Analytics
                                </span>
                            </h1>
                            <p class="text-xs text-slate-300 mt-0.5">
                                Pantau adopsi digital sekolah, keaktifan guru & siswa, utilisasi modul, serta rekam audit keamanan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FILTER PERIODE & CETAK -->
                <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                    <div class="inline-flex p-1 rounded-2xl bg-[#021124] border border-white/10 text-xs">
                        <a href="{{ route('system-analytics.index', ['period' => 'today']) }}"
                           class="px-3 py-1.5 rounded-xl font-bold transition-all {{ $period === 'today' ? 'bg-gradient-to-r from-elevate-accent to-elevate-primary text-white shadow-sm' : 'text-slate-400 hover:text-white' }}">
                            Hari Ini
                        </a>
                        <a href="{{ route('system-analytics.index', ['period' => 'this_week']) }}"
                           class="px-3 py-1.5 rounded-xl font-bold transition-all {{ $period === 'this_week' ? 'bg-gradient-to-r from-elevate-accent to-elevate-primary text-white shadow-sm' : 'text-slate-400 hover:text-white' }}">
                            Minggu Ini
                        </a>
                        <a href="{{ route('system-analytics.index', ['period' => 'this_month']) }}"
                           class="px-3 py-1.5 rounded-xl font-bold transition-all {{ $period === 'this_month' ? 'bg-gradient-to-r from-elevate-accent to-elevate-primary text-white shadow-sm' : 'text-slate-400 hover:text-white' }}">
                            Bulan Ini
                        </a>
                    </div>

                    <a href="{{ route('system-analytics.print', ['period' => $period]) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/15 text-white text-xs font-bold transition-all hover:border-elevate-accent/50 shadow-sm">
                        <i class="ph-bold ph-printer text-base text-elevate-accent"></i>
                        Cetak Laporan
                    </a>
                </div>
            </div>

            <!-- PERIODE AKTIF BAR -->
            <div class="mt-4 pt-4 border-t border-white/10 flex flex-wrap items-center justify-between text-xs text-slate-400 gap-2">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-calendar text-elevate-accent text-sm"></i>
                    <span>Periode Ditampilkan:</span>
                    <strong class="text-white">{{ $periodLabel }}</strong>
                </div>
                <div class="flex items-center gap-2 text-[11px] text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Pencatatan sesi & audit berjalan otomatis</span>
                </div>
            </div>
        </div>

        <!-- KPI SUMMARY CARDS -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- 1. Total Sesi Login -->
            <div class="fluent-card p-4 rounded-2xl flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Sesi Login</span>
                    <div class="w-8 h-8 rounded-xl bg-sky-500/15 text-sky-400 flex items-center justify-center">
                        <i class="ph-bold ph-sign-in text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-black text-white tracking-tight">{{ number_format($totalLogins) }}</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Sesi Masuk Sistem</p>
                </div>
            </div>

            <!-- 2. Guru Aktif -->
            <div class="fluent-card p-4 rounded-2xl flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Guru Aktif</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center">
                        <i class="ph-bold ph-chalkboard-teacher text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-2xl font-black text-white tracking-tight">{{ $uniqueTeachers }}</h3>
                        <span class="text-xs text-slate-400">/ {{ $totalRegisteredTeachers }}</span>
                    </div>
                    <p class="text-[11px] text-emerald-400 font-bold mt-0.5">{{ $teacherAdoptionRate }}% Terlibat</p>
                </div>
            </div>

            <!-- 3. Siswa Akses -->
            <div class="fluent-card p-4 rounded-2xl flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Siswa Aktif</span>
                    <div class="w-8 h-8 rounded-xl bg-purple-500/15 text-purple-400 flex items-center justify-center">
                        <i class="ph-bold ph-student text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-black text-white tracking-tight">{{ number_format($uniqueStudents) }}</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Siswa Buka Portal/LMS</p>
                </div>
            </div>

            <!-- 4. Jurnal KBM -->
            <div class="fluent-card p-4 rounded-2xl flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jurnal KBM</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-500/15 text-amber-400 flex items-center justify-center">
                        <i class="ph-bold ph-notebook text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-black text-white tracking-tight">{{ number_format($kbmSessionsCount) }}</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Sesi Jam Mengajar</p>
                </div>
            </div>

            <!-- 5. Scan Presensi -->
            <div class="fluent-card p-4 rounded-2xl flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Scan Presensi</span>
                    <div class="w-8 h-8 rounded-xl bg-teal-500/15 text-teal-400 flex items-center justify-center">
                        <i class="ph-bold ph-qr-code text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-black text-white tracking-tight">{{ number_format($attendanceScansCount) }}</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Record Presensi</p>
                </div>
            </div>

            <!-- 6. Total Audit Log -->
            <div class="fluent-card p-4 rounded-2xl flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aksi Audit</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-500/15 text-rose-400 flex items-center justify-center">
                        <i class="ph-bold ph-shield-check text-base"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-black text-white tracking-tight">{{ number_format($totalAuditActions) }}</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Mutasi Data Tercatat</p>
                </div>
            </div>
        </div>

        <!-- CHARTS SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- TREN LOGIN HARIAN (2 Kolom) -->
            <div class="lg:col-span-2 fluent-card p-6 rounded-3xl">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-black text-white tracking-tight flex items-center gap-2">
                            <i class="ph-duotone ph-chart-bar text-elevate-accent text-lg"></i>
                            Tren Keaktifan Login
                        </h2>
                        <p class="text-xs text-slate-400">Komparasi jumlah sesi login antara Guru/Staf vs Siswa</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="flex items-center gap-1.5 text-slate-300">
                            <span class="w-3 h-3 rounded-md bg-sky-400"></span> Guru/Staf
                        </span>
                        <span class="flex items-center gap-1.5 text-slate-300">
                            <span class="w-3 h-3 rounded-md bg-purple-500"></span> Siswa
                        </span>
                    </div>
                </div>

                <div id="loginTrendChart" class="w-full h-64"></div>
            </div>

            <!-- DISTRIBUSI PERANGKAT & ADOPSI (1 Kolom) -->
            <div class="fluent-card p-6 rounded-3xl flex flex-col justify-between">
                <div>
                    <h2 class="text-base font-black text-white tracking-tight flex items-center gap-2 mb-1">
                        <i class="ph-duotone ph-devices text-elevate-accent text-lg"></i>
                        Distribusi Perangkat
                    </h2>
                    <p class="text-xs text-slate-400 mb-4">Jenis perangkat yang digunakan saat mengakses aplikasi</p>

                    <div id="deviceBreakdownChart" class="w-full h-44 flex items-center justify-center"></div>
                </div>

                <div class="space-y-2 mt-4 pt-4 border-t border-white/10">
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-slate-300">
                            <i class="ph-bold ph-desktop text-sky-400"></i> Desktop / Laptop
                        </span>
                        <strong class="text-white">{{ number_format($deviceDesktop) }} sesi</strong>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-slate-300">
                            <i class="ph-bold ph-device-mobile text-emerald-400"></i> Smartphone (HP)
                        </span>
                        <strong class="text-white">{{ number_format($deviceMobile) }} sesi</strong>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-slate-300">
                            <i class="ph-bold ph-tablet text-purple-400"></i> Tablet / iPad
                        </span>
                        <strong class="text-white">{{ number_format($deviceTablet) }} sesi</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- RADAR KEAKTIFAN GURU (SUPERVISI KEPALA SEKOLAH) -->
        <div class="fluent-card rounded-3xl overflow-hidden">
            <div class="p-6 border-b border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-black text-white tracking-tight flex items-center gap-2">
                        <i class="ph-duotone ph-chalkboard-teacher text-elevate-accent text-lg"></i>
                        Radar Keaktifan Guru & KBM
                    </h2>
                    <p class="text-xs text-slate-400">Pantau guru yang tertib mengisi jurnal mengajar dan yang membutuhkan supervisi</p>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/20 font-bold">
                        {{ $uniqueTeachers }} Aktif
                    </span>
                    <span class="px-3 py-1 rounded-full bg-rose-500/15 text-rose-400 border border-rose-500/20 font-bold">
                        {{ max(0, $totalRegisteredTeachers - $uniqueTeachers) }} Belum Login
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#021124]/75 text-slate-400 font-bold border-b border-white/10 uppercase tracking-wider text-[10px] sticky top-0 z-10 backdrop-blur-md">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4">Nama Guru</th>
                            <th class="py-3 px-4">Role / Jabatan</th>
                            <th class="py-3 px-4 text-center">Jurnal KBM Terisi</th>
                            <th class="py-3 px-4">Login Terakhir</th>
                            <th class="py-3 px-4 text-center">Status Keaktifan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($teachers as $index => $teacher)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-elevate-accent to-elevate-primary flex items-center justify-center text-white font-bold text-xs">
                                            {{ substr($teacher->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">{{ $teacher->name }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $teacher->nip ?? 'NIP -' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-slate-300">
                                    {{ $teacher->position ?? ($teacher->role ?? 'Guru') }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="font-black {{ $teacher->sessions_count > 0 ? 'text-emerald-400' : 'text-slate-400' }}">
                                        {{ $teacher->sessions_count }} Sesi
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-300">
                                    {{ $teacher->last_login ? $teacher->last_login->translatedFormat('d M Y, H:i') : 'Belum pernah' }}
                                    <span class="block text-[10px] text-slate-400">{{ $teacher->last_login_human }}</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($teacher->is_active_in_period)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-[10px] font-bold">
                                            <i class="ph-bold ph-check-circle"></i> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-500/15 text-rose-400 border border-rose-500/30 text-[10px] font-bold" title="Belum beraktivitas pada periode ini">
                                            <i class="ph-bold ph-warning-circle"></i> Perlu Perhatian
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-400">Tidak ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- AUDIT TRAIL LOGS LIVE FEED -->
        <div class="fluent-card rounded-3xl overflow-hidden">
            <div class="p-6 border-b border-white/10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-black text-white tracking-tight flex items-center gap-2">
                        <i class="ph-duotone ph-shield-warning text-rose-400 text-lg"></i>
                        Audit Trail & Log Keamanan Sistem
                    </h2>
                    <p class="text-xs text-slate-400">Rekam jejak mutasi data (create, update, delete, import, reset) secara real-time</p>
                </div>

                <!-- FILTER AUDIT LOGS -->
                <form action="{{ route('system-analytics.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                    <input type="hidden" name="period" value="{{ $period }}">

                    <select name="filter_module" onchange="this.form.submit()"
                            class="px-3 py-1.5 rounded-xl bg-[#021124] border border-white/10 text-xs text-white focus:outline-none focus:border-elevate-accent">
                        <option value="">Semua Modul</option>
                        <option value="auth" {{ request('filter_module') === 'auth' ? 'selected' : '' }}>Auth & Sesi</option>
                        <option value="kbm" {{ request('filter_module') === 'kbm' ? 'selected' : '' }}>KBM & Jadwal</option>
                        <option value="presensi_laporan" {{ request('filter_module') === 'presensi_laporan' ? 'selected' : '' }}>Presensi</option>
                        <option value="lms" {{ request('filter_module') === 'lms' ? 'selected' : '' }}>LMS</option>
                        <option value="cbt" {{ request('filter_module') === 'cbt' ? 'selected' : '' }}>CBT & Ujian</option>
                        <option value="master_siswa" {{ request('filter_module') === 'master_siswa' ? 'selected' : '' }}>Siswa & Kelas</option>
                        <option value="penilaian" {{ request('filter_module') === 'penilaian' ? 'selected' : '' }}>Penilaian</option>
                        <option value="perpustakaan" {{ request('filter_module') === 'perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
                    </select>

                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aksi/pengguna..."
                               class="pl-8 pr-3 py-1.5 rounded-xl bg-[#021124] border border-white/10 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-elevate-accent">
                        <i class="ph-bold ph-magnifying-glass absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-elevate-accent/20 hover:bg-elevate-accent/30 text-elevate-accent font-bold text-xs border border-elevate-accent/30 transition-colors">
                        Filter
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#021124]/75 text-slate-400 font-bold border-b border-white/10 uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4 w-36">Waktu</th>
                            <th class="py-3 px-4 w-44">Pengguna</th>
                            <th class="py-3 px-4 w-28">Modul</th>
                            <th class="py-3 px-4 w-24">Aksi</th>
                            <th class="py-3 px-4">Deskripsi Kegiatan</th>
                            <th class="py-3 px-4 w-32">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($auditLogs as $log)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 text-slate-400 whitespace-nowrap">
                                    <span class="text-white font-medium">{{ $log->created_at->format('H:i:s') }}</span>
                                    <span class="block text-[10px] text-slate-400">{{ $log->created_at->translatedFormat('d M Y') }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <p class="font-bold text-white">{{ $log->user_name }}</p>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-white/10 text-slate-300">
                                        {{ $log->role ?? 'User' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded bg-elevate-accent/15 text-elevate-accent border border-elevate-accent/30 font-mono text-[10px] uppercase font-bold">
                                        {{ $log->module }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $actionBadge = match(strtolower($log->action)) {
                                            'create' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                            'update' => 'bg-sky-500/20 text-sky-400 border-sky-500/30',
                                            'delete', 'failed_login' => 'bg-rose-500/20 text-rose-400 border-rose-500/30',
                                            'login'  => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                                            default  => 'bg-white/10 text-slate-300 border-white/15',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded border text-[10px] uppercase font-bold {{ $actionBadge }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-300 max-w-md truncate" title="{{ $log->description }}">
                                    {{ $log->description }}
                                </td>
                                <td class="py-3 px-4 text-slate-400 font-mono text-[11px]">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    <i class="ph-duotone ph-clipboard-text text-3xl mb-2 text-slate-400 block"></i>
                                    Belum ada catatan log aktivitas yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            @if($auditLogs->hasPages())
                <div class="p-4 border-t border-white/10 bg-[#021124]/50">
                    {{ $auditLogs->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- SCRIPT CHARTS (APEXCHARTS CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. CHART TREN LOGIN
            const trendOptions = {
                series: [
                    {
                        name: 'Guru / Staf',
                        data: @json($trendTeachers)
                    },
                    {
                        name: 'Siswa',
                        data: @json($trendStudents)
                    }
                ],
                chart: {
                    type: 'area',
                    height: 250,
                    toolbar: { show: false },
                    fontFamily: 'inherit',
                    background: 'transparent'
                },
                colors: ['#38bdf8', '#a855f7'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [0, 95, 100]
                    }
                },
                xaxis: {
                    categories: @json($trendDates),
                    labels: { style: { colors: '#94a3b8', fontSize: '10px' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: { style: { colors: '#94a3b8', fontSize: '10px' } }
                },
                grid: {
                    borderColor: 'rgba(255, 255, 255, 0.07)',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'dark'
                },
                legend: { show: false }
            };

            const trendChart = new ApexCharts(document.querySelector("#loginTrendChart"), trendOptions);
            trendChart.render();

            // 2. CHART PERANGKAT (DONUT)
            const deviceDesktop = {{ $deviceDesktop }};
            const deviceMobile = {{ $deviceMobile }};
            const deviceTablet = {{ $deviceTablet }};
            const totalDevices = deviceDesktop + deviceMobile + deviceTablet;

            const deviceOptions = {
                series: totalDevices > 0 ? [deviceDesktop, deviceMobile, deviceTablet] : [1, 0, 0],
                chart: {
                    type: 'donut',
                    height: 180,
                    background: 'transparent'
                },
                labels: ['Desktop', 'Mobile', 'Tablet'],
                colors: ['#38bdf8', '#10b981', '#a855f7'],
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Sesi',
                                    color: '#94a3b8',
                                    fontSize: '11px',
                                    formatter: () => totalDevices
                                },
                                value: {
                                    color: '#ffffff',
                                    fontSize: '18px',
                                    fontWeight: 800
                                }
                            }
                        }
                    }
                },
                stroke: { show: false },
                legend: { show: false },
                tooltip: { theme: 'dark' }
            };

            const deviceChart = new ApexCharts(document.querySelector("#deviceBreakdownChart"), deviceOptions);
            deviceChart.render();
        });
    </script>
</x-app-layout>
