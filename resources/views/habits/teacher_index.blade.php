<x-app-layout>
    {{-- IMPORT FONT & CUSTOM STYLES --}}
    <style>
        @import url('https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap');

        .font-jakarta {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        /* Memastikan font diterapkan ke seluruh kontainer */
        .page-container {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    <div class="page-container p-4 md:p-8 space-y-8 min-h-screen bg-slate-50 font-jakarta">
        
        {{-- HERO SECTION --}}
        <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-slate-800 to-slate-900 p-8 md:p-12 text-white shadow-2xl shadow-blue-900/20 overflow-hidden group border border-white/10">
            {{-- Elemen Dekoratif --}}
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20 group-hover:opacity-30 transition-opacity duration-1000"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-blue-200 text-[10px] font-extrabold uppercase tracking-widest mb-6 backdrop-blur-sm shadow-sm">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-400"></span>
                        </span>
                        Panel Monitoring Guru
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-4 leading-tight">
                        Monitoring <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-white">7 Kebiasaan</span>
                    </h1>
                    <p class="text-blue-100/80 text-sm md:text-base max-w-xl leading-relaxed font-medium">
                        Pantau aktivitas harian siswa SMPN 3 Lakbok dalam membangun karakter unggul secara real-time.
                    </p>
                </div>

                {{-- FILTER FORM DI DALAM HERO --}}
                <div class="w-full lg:w-auto">
                    <form id="filterForm" action="{{ route('teacher.habits.index') }}" method="GET" class="glass-card p-6 rounded-3xl shadow-xl flex flex-col md:flex-row gap-4 border-white/20 relative">
                        {{-- Loading Overlay --}}
                        <div id="formLoading" class="hidden absolute inset-0 bg-white/60 backdrop-blur-[2px] z-10 rounded-3xl flex items-center justify-center">
                            <i class="ph-bold ph-circle-notch animate-spin text-blue-600 text-2xl"></i>
                        </div>

                        <div class="flex-1 min-w-[180px]">
                            <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Pilih Tanggal</label>
                            <div class="relative group">
                                <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-blue-500"></i>
                                <input type="date" name="date" value="{{ $date }}" 
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-slate-200 rounded-2xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700 uppercase tracking-wider" 
                                    onchange="submitFilter()">
                            </div>
                        </div>

                        <div class="flex-1 min-w-[220px]">
                            <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Pilih Kelas</label>
                            <div class="relative group">
                                <i class="ph-bold ph-users absolute left-4 top-1/2 -translate-y-1/2 text-blue-500"></i>
                                <select name="class_id" 
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-slate-200 rounded-2xl text-xs font-bold focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700" 
                                    onchange="submitFilter()">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($classId)
            {{-- STATISTIK KELAS (TEMA BIRU) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-enter" style="animation-delay: 100ms">
                <div class="glass-card p-6 rounded-[2rem] shadow-sm flex items-center gap-5 group hover:shadow-lg transition-all duration-300 border-white">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Sudah Lapor</p>
                        <p class="text-3xl font-black text-slate-800 tracking-tight">{{ $stats['submitted'] }} <span class="text-xs font-bold text-slate-400 uppercase">Siswa</span></p>
                    </div>
                </div>

                <div class="glass-card p-6 rounded-[2rem] shadow-sm flex items-center gap-5 group hover:shadow-lg transition-all duration-300 border-white">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-clock-countdown"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Belum Lapor</p>
                        <p class="text-3xl font-black text-slate-800 tracking-tight">{{ $stats['missing'] }} <span class="text-xs font-bold text-slate-400 uppercase">Siswa</span></p>
                    </div>
                </div>

                <div class="bg-blue-600 p-6 rounded-[2rem] shadow-xl shadow-blue-900/10 flex items-center gap-5 group hover:bg-blue-700 transition-all duration-300 border border-blue-500/20">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 text-white flex items-center justify-center text-2xl group-hover:rotate-12 transition-transform">
                        <i class="ph-fill ph-chart-pie-slice"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-blue-200 uppercase tracking-widest mb-1">Partisipasi</p>
                        <p class="text-3xl font-black text-white tracking-tight">{{ $stats['percentage'] }}%</p>
                    </div>
                </div>
            </div>

            {{-- DAFTAR SISWA --}}
            <div class="animate-enter bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden mb-10" style="animation-delay: 200ms">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50 border-b border-slate-100 text-slate-500 uppercase text-[10px] font-extrabold tracking-[0.2em]">
                            <tr>
                                <th class="px-8 py-6">Nama Siswa</th>
                                <th class="px-6 py-6">Status</th>
                                <th class="px-6 py-6">Waktu Lapor</th>
                                <th class="px-8 py-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($students as $student)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 font-extrabold text-xs border border-blue-100 shadow-inner">
                                                {{ substr($student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-700 group-hover:text-blue-600 transition-colors uppercase tracking-tight">{{ $student->name }}</div>
                                                <div class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">{{ $student->student_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($student->habit_status == 'submitted')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-100 text-blue-700 text-[10px] font-extrabold uppercase tracking-widest">
                                                <i class="ph-fill ph-check-circle text-xs"></i> Sudah Lapor
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-100 text-slate-400 text-[10px] font-extrabold uppercase tracking-widest">
                                                <i class="ph-bold ph-minus-circle text-xs"></i> Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($student->habit_data)
                                            <div class="flex items-center gap-2 text-slate-600 font-extrabold text-xs">
                                                <i class="ph-bold ph-clock text-blue-500"></i>
                                                {{ $student->habit_data->created_at->format('H:i') }} <span class="text-[9px] text-slate-400">WIB</span>
                                            </div>
                                        @else
                                            <span class="text-slate-300 text-xs font-bold">--:--</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        @if($student->habit_data)
                                            <button onclick="openDetail({{ $student->habit_data->id }})" 
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-white font-extrabold text-[10px] uppercase tracking-widest bg-blue-50 hover:bg-blue-600 px-5 py-2.5 rounded-2xl transition-all active:scale-90 border border-blue-100 shadow-sm">
                                                <i class="ph-bold ph-eye text-sm"></i> Detail Laporan
                                            </button>
                                        @else
                                            <span class="text-slate-300 text-[10px] font-extrabold uppercase tracking-widest italic">Belum Ada Data</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <p class="text-slate-400 font-extrabold uppercase tracking-widest text-xs italic">Siswa tidak ditemukan untuk filter ini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- EMPTY STATE (TEMA BIRU) --}}
            <div class="animate-enter text-center py-32 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 shadow-inner group hover:border-blue-300 transition-colors" style="animation-delay: 100ms">
                <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-8 text-blue-500 group-hover:scale-110 transition-transform shadow-inner">
                    <i class="ph-duotone ph-magnifying-glass text-5xl"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-700 tracking-tight">Menunggu Pemilihan Kelas</h3>
                <p class="text-slate-500 text-sm max-w-sm mx-auto mt-3 leading-relaxed font-medium">Silakan pilih kelas dan tanggal melalui formulir di atas untuk menampilkan data monitoring siswa.</p>
            </div>
        @endif

    </div>

    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="closeDetail()"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-[3rem] w-full max-w-2xl shadow-2xl relative transform transition-all overflow-hidden border border-white/20">
                <button onclick="closeDetail()" class="absolute top-8 right-8 z-10 text-slate-400 hover:text-rose-500 p-2 rounded-full hover:bg-rose-50 transition-all active:scale-90">
                    <i class="ph-bold ph-x text-2xl"></i>
                </button>
                <div id="modalContent" class="p-10 font-jakarta">
                    {{-- Konten AJAX dimuat di sini --}}
                    <div class="flex flex-col items-center justify-center py-24">
                        <i class="ph-bold ph-circle-notch animate-spin text-5xl text-blue-600"></i>
                        <p class="mt-4 text-slate-400 text-[10px] font-extrabold uppercase tracking-[0.3em]">Memproses Laporan...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function submitFilter() {
            document.getElementById('formLoading').classList.remove('hidden');
            document.getElementById('filterForm').submit();
        }

        function openDetail(id) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            content.innerHTML = `
                <div class="flex flex-col items-center justify-center py-24">
                    <i class="ph-bold ph-circle-notch animate-spin text-5xl text-blue-600"></i>
                    <p class="mt-4 text-slate-400 text-[10px] font-extrabold uppercase tracking-[0.3em]">Mengambil Data Dari Server...</p>
                </div>`;
            
            fetch(`{{ url('/teacher/habits/detail') }}/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.text();
                })
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(err => {
                    content.innerHTML = `
                        <div class="text-center py-20">
                            <i class="ph-bold ph-warning-circle text-5xl text-rose-500 mb-4"></i>
                            <h3 class="text-lg font-bold text-slate-800">Gagal Memuat Data</h3>
                            <p class="text-slate-500 text-sm mb-6 font-medium">Koneksi terputus atau terjadi kesalahan sistem.</p>
                            <button onclick="openDetail(${id})" class="bg-blue-600 text-white px-8 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-500/20 active:scale-95">Coba Lagi</button>
                        </div>`;
                });
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</x-app-layout>