<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="mb-8 relative z-10">
                <x-hero-section
                    badge="DATABASE ALUMNI"
                    badgeIcon="ph-fill ph-users-three"
                    showcaseIcon="ph-duotone ph-graduation-cap"
                    showcaseTitle="Tracer Study"
                    showcaseSubtitle="Sebaran Lulusan">
                    <x-slot:title>
                        <span class="block text-slate-100">Tracer Study</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Alumni & Kelulusan
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Pantau rekam jejak dan sebaran lulusan ke SMA, SMK, MA, Pesantren, maupun aktivitas lanjutan lainnya secara terstruktur.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-chart-pie-slice text-sky-400"></i> Sebaran Jalur
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-buildings text-emerald-400"></i> SMA/SMK/MA/Ponpes
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-calendar text-cyan-400"></i> Lintas Angkatan
                        </span>
                    </x-slot:chips>
                    <x-slot:showcaseStats>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                            <i class="ph-fill ph-users text-sky-400 text-sm"></i>
                            <span class="text-xs font-bold text-slate-300">Total:</span>
                            <span class="text-sm font-black text-white font-mono">{{ isset($stats['total']) ? $stats['total'] : $alumni->total() }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-emerald-500/30 backdrop-blur-md">
                            <i class="ph-fill ph-check-circle text-emerald-400 text-sm"></i>
                            <span class="text-xs font-bold text-slate-300">Lanjut:</span>
                            <span class="text-sm font-black text-emerald-400 font-mono">{{ $stats['lanjut_sekolah'] ?? 0 }}</span>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>

            {{-- TOOLBAR --}}
            <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2rem] border border-white/10 shadow-2xl mb-6 p-4 flex flex-col md:flex-row items-center justify-between gap-4 relative z-20">
                <form method="GET" class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                    {{-- Filter Tahun --}}
                    <div class="relative">
                        <select name="year" onchange="this.form.submit()" class="w-full rounded-xl border-white/15 text-sm font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] py-2.5 pl-4 pr-10 bg-[#021124]/90 appearance-none cursor-pointer">
                            <option value="" class="bg-[#021124] text-white">-- Semua Angkatan --</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" class="bg-[#021124] text-white" {{ request('year') == $year ? 'selected' : '' }}>Lulusan {{ $year }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                    </div>

                    {{-- Filter Sekolah Lanjutan --}}
                    <div class="relative">
                        <select name="activity" onchange="this.form.submit()" class="w-full rounded-xl border-white/15 text-sm font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] py-2.5 pl-4 pr-10 bg-[#021124]/90 appearance-none cursor-pointer">
                            <option value="" class="bg-[#021124] text-white">-- Semua Jalur --</option>
                            <option value="SMA" class="bg-[#021124] text-white" {{ request('activity') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="SMK" class="bg-[#021124] text-white" {{ request('activity') == 'SMK' ? 'selected' : '' }}>SMK</option>
                            <option value="MA" class="bg-[#021124] text-white" {{ request('activity') == 'MA' ? 'selected' : '' }}>MA</option>
                            <option value="Pesantren" class="bg-[#021124] text-white" {{ request('activity') == 'Pesantren' ? 'selected' : '' }}>Pesantren</option>
                            <option value="Tidak Lanjut" class="bg-[#021124] text-white" {{ request('activity') == 'Tidak Lanjut' ? 'selected' : '' }}>Tidak Lanjut</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                    </div>
                </form>

                <div class="flex flex-wrap gap-2 w-full md:w-auto justify-end">
                    {{-- Search Form --}}
                    <form method="GET" class="relative w-full md:w-48 lg:w-56 group">
                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#56bbf1] transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NISN..." 
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl border-white/15 bg-[#021124]/90 text-sm font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] transition-all shadow-sm placeholder:text-slate-500">
                    </form>
                    
                    {{-- TOMBOL TESTIMONI --}}
                    <a href="{{ route('admin.alumni.testimonials') }}" class="px-4 py-2.5 bg-[#56bbf1]/10 text-[#56bbf1] border border-[#56bbf1]/20 rounded-xl font-bold text-sm hover:bg-[#56bbf1] hover:text-white flex items-center gap-2 transition-all shadow-sm" title="Lihat Testimoni">
                        <i class="ph-bold ph-quotes"></i> <span class="hidden lg:inline">Testimoni</span>
                    </a>

                    {{-- TOMBOL IMPORT --}}
                    <a href="{{ route('admin.alumni.import') }}" class="px-4 py-2.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl font-bold text-sm hover:bg-emerald-500 hover:text-white flex items-center gap-2 transition-all shadow-sm" title="Import Data Excel/CSV">
                        <i class="ph-bold ph-upload-simple"></i>
                    </a>

                    {{-- TOMBOL PDF --}}
                    <a href="{{ route('admin.alumni.export_pdf', request()->all()) }}" target="_blank" class="px-4 py-2.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-xl font-bold text-sm hover:bg-rose-500 hover:text-white flex items-center gap-2 transition-all shadow-sm" title="Export Laporan PDF">
                        <i class="ph-bold ph-file-pdf"></i>
                    </a>
                </div>
            </div>

            {{-- TABLE DATA --}}
            <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-slate-300 border-collapse">
                        <thead class="text-xs font-bold text-slate-400 uppercase bg-white/[0.03] border-b border-white/10">
                            <tr>
                                <th class="px-6 py-5">Identitas Alumni</th>
                                <th class="px-6 py-5">Angkatan</th>
                                <th class="px-6 py-5">Sekolah Lanjutan</th>
                                <th class="px-6 py-5">Kontak</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($alumni as $student)
                            <tr class="hover:bg-white/[0.03] transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-white/5 border border-white/10 overflow-hidden shrink-0 flex items-center justify-center">
                                            @if($student->photo_path)
                                                <img src="{{ asset('storage/'.$student->photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-300 font-bold bg-white/5">{{ substr($student->name, 0, 2) }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-white group-hover:text-[#56bbf1] transition-colors line-clamp-1">{{ $student->name }}</div>
                                            <div class="text-xs text-slate-400 font-medium font-mono mt-0.5">{{ $student->nisn ?? $student->student_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold bg-white/5 text-slate-300 border border-white/10 tracking-wider">
                                        <i class="ph-fill ph-graduation-cap text-[#56bbf1]"></i> 
                                        {{ $student->graduation_year ?? (\Carbon\Carbon::parse($student->graduated_date)->year ?? '-') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        // LOGIKA BARU: Jika tidak ada profil ATAU statusnya "Mencari Kerja", anggap saja "Belum Mengisi"
                                        $isBelumMengisi = !$student->alumniProfile || $student->alumniProfile->activity_status === 'Mencari Kerja';
                                    @endphp

                                    @if(!$isBelumMengisi)
                                        @php $status = $student->alumniProfile->activity_status; @endphp
                                        
                                        {{-- JIKA STATUSNYA ADA, TAMPILKAN WARNANYA --}}
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide border
                                            {{ $status == 'SMA' ? 'bg-sky-500/15 text-sky-300 border-sky-500/30' : '' }}
                                            {{ $status == 'SMK' ? 'bg-orange-500/15 text-orange-300 border-orange-500/30' : '' }}
                                            {{ $status == 'MA' ? 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30' : '' }}
                                            {{ $status == 'Pesantren' ? 'bg-teal-500/15 text-teal-300 border-teal-500/30' : '' }}
                                            {{ $status == 'Bekerja' ? 'bg-white/10 text-slate-300 border-white/10' : '' }}
                                            {{ $status == 'Tidak Lanjut' ? 'bg-rose-500/15 text-rose-300 border-rose-500/30' : '' }}">
                                            {{ $status }}
                                        </span>
                                        
                                        <div class="text-xs text-white font-bold mt-1.5 truncate max-w-[200px]" title="{{ $student->alumniProfile->campus_name ?? $student->alumniProfile->company_name }}">
                                            {{ $student->alumniProfile->campus_name ?? $student->alumniProfile->company_name ?? '-' }}
                                        </div>
                                    @else
                                        {{-- JIKA BENAR-BENAR BELUM ADA PROFIL ATAU STATUSNYA MENCARI KERJA --}}
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30 uppercase tracking-wider">
                                            <i class="ph-fill ph-warning-circle text-sm text-amber-400"></i> Belum Mengisi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                     <div class="text-xs font-medium space-y-1">
                                        @if($student->alumniProfile && $student->alumniProfile->phone_number)
                                            <div class="flex items-center gap-2 font-bold text-emerald-400">
                                                <i class="ph-fill ph-whatsapp-logo text-emerald-400 text-sm"></i> {{ $student->alumniProfile->phone_number }}
                                            </div>
                                        @else
                                            <div class="text-slate-500">-</div>
                                        @endif
                                     </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- TOMBOL LIHAT BUKU INDUK --}}
                                        <a href="{{ route('students.show', $student->id) }}" target="_blank" class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:bg-emerald-500 hover:border-emerald-500 hover:text-white transition-all shadow-sm" title="Lihat Arsip Buku Induk">
                                            <i class="ph-bold ph-book-open-text text-lg"></i>
                                        </a>

                                        {{-- TOMBOL DETAIL ALUMNI --}}
                                        <a href="{{ route('admin.alumni.show', $student->id) }}" class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:bg-[#56bbf1] hover:border-[#56bbf1] hover:text-white transition-all shadow-sm" title="Lihat Detail Alumni">
                                            <i class="ph-bold ph-eye text-lg"></i>
                                        </a>
                                        
                                        {{-- TOMBOL EDIT ALUMNI --}}
                                        <a href="{{ route('admin.alumni.edit', $student->id) }}" class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:bg-amber-500 hover:border-amber-500 hover:text-white transition-all shadow-sm" title="Edit Data Tracer Study">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/10">
                                            <i class="ph-duotone ph-users-three text-4xl text-slate-400"></i>
                                        </div>
                                        <span class="font-bold text-slate-300 text-base">Belum ada data alumni yang ditemukan.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-white/10 bg-white/[0.02]">
                    {{ $alumni->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- SWEETALERT 2 NOTIFICATION SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#0d52a1',
                    confirmButtonText: 'Tutup',
                    background: '#031d3d',
                    color: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2.5rem] shadow-2xl border border-white/10 font-sans'
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#e11d48',
                    confirmButtonText: 'Coba Lagi',
                    background: '#031d3d',
                    color: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2.5rem] shadow-2xl border border-white/10 font-sans'
                    }
                });
            @endif
        });
    </script>
</x-app-layout>