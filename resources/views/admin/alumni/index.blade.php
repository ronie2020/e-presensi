<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
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
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm mb-6 p-4 flex flex-col md:flex-row items-center justify-between gap-4 relative z-20">
                <form method="GET" class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                    {{-- Filter Tahun --}}
                    <div class="relative">
                        <select name="year" onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 text-sm font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary py-2.5 pl-4 pr-10 bg-slate-50 appearance-none cursor-pointer">
                            <option value="">-- Semua Angkatan --</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>Lulusan {{ $year }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                    </div>

                    {{-- Filter Sekolah Lanjutan --}}
                    <div class="relative">
                        <select name="activity" onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 text-sm font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary py-2.5 pl-4 pr-10 bg-slate-50 appearance-none cursor-pointer">
                            <option value="">-- Semua Jalur --</option>
                            <option value="SMA" {{ request('activity') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="SMK" {{ request('activity') == 'SMK' ? 'selected' : '' }}>SMK</option>
                            <option value="MA" {{ request('activity') == 'MA' ? 'selected' : '' }}>MA</option>
                            <option value="Pesantren" {{ request('activity') == 'Pesantren' ? 'selected' : '' }}>Pesantren</option>
                            <option value="Tidak Lanjut" {{ request('activity') == 'Tidak Lanjut' ? 'selected' : '' }}>Tidak Lanjut</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                    </div>
                </form>

                <div class="flex flex-wrap gap-2 w-full md:w-auto justify-end">
                    {{-- Search Form --}}
                    <form method="GET" class="relative w-full md:w-48 lg:w-56 group">
                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NISN..." 
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary transition-all shadow-sm">
                    </form>
                    
                    {{-- TOMBOL TESTIMONI --}}
                    <a href="{{ route('admin.alumni.testimonials') }}" class="px-4 py-2.5 bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 rounded-xl font-bold text-sm hover:bg-elevate-primary hover:text-white flex items-center gap-2 transition-all shadow-sm" title="Lihat Testimoni">
                        <i class="ph-bold ph-quotes"></i> <span class="hidden lg:inline">Testimoni</span>
                    </a>

                    {{-- TOMBOL IMPORT --}}
                    <a href="{{ route('admin.alumni.import') }}" class="px-4 py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl font-bold text-sm hover:bg-emerald-600 hover:text-white flex items-center gap-2 transition-all shadow-sm" title="Import Data Excel/CSV">
                        <i class="ph-bold ph-upload-simple"></i>
                    </a>

                    {{-- TOMBOL PDF --}}
                    <a href="{{ route('admin.alumni.export_pdf', request()->all()) }}" target="_blank" class="px-4 py-2.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl font-bold text-sm hover:bg-rose-600 hover:text-white flex items-center gap-2 transition-all shadow-sm" title="Export Laporan PDF">
                        <i class="ph-bold ph-file-pdf"></i>
                    </a>
                </div>
            </div>

            {{-- TABLE DATA --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden relative">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-slate-600 border-collapse">
                        <thead class="text-xs font-bold text-slate-400 uppercase bg-slate-50/80 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5">Identitas Alumni</th>
                                <th class="px-6 py-5">Angkatan</th>
                                <th class="px-6 py-5">Sekolah Lanjutan</th>
                                <th class="px-6 py-5">Kontak</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($alumni as $student)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                            @if($student->photo_path)
                                                <img src="{{ asset('storage/'.$student->photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold bg-white">{{ substr($student->name, 0, 2) }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors line-clamp-1">{{ $student->name }}</div>
                                            <div class="text-xs text-slate-400 font-medium font-mono mt-0.5">{{ $student->nisn ?? $student->student_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 tracking-wider">
                                        <i class="ph-fill ph-graduation-cap"></i> 
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
                                            {{ $status == 'SMA' ? 'bg-elevate-primary/10 text-elevate-primary border-elevate-primary/20' : '' }}
                                            {{ $status == 'SMK' ? 'bg-orange-50 text-orange-600 border-orange-100' : '' }}
                                            {{ $status == 'MA' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                            {{ $status == 'Pesantren' ? 'bg-teal-50 text-teal-600 border-teal-100' : '' }}
                                            {{ $status == 'Bekerja' ? 'bg-slate-100 text-slate-600 border-slate-200' : '' }}
                                            {{ $status == 'Tidak Lanjut' ? 'bg-red-50 text-red-600 border-red-200' : '' }}">
                                            {{ $status }}
                                        </span>
                                        
                                        <div class="text-xs text-elevate-dark font-bold mt-1.5 truncate max-w-[200px]" title="{{ $student->alumniProfile->campus_name ?? $student->alumniProfile->company_name }}">
                                            {{ $student->alumniProfile->campus_name ?? $student->alumniProfile->company_name ?? '-' }}
                                        </div>
                                    @else
                                        {{-- JIKA BENAR-BENAR BELUM ADA PROFIL ATAU STATUSNYA MENCARI KERJA --}}
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase tracking-wider">
                                            <i class="ph-fill ph-warning-circle text-sm"></i> Belum Mengisi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                     <div class="text-xs font-medium space-y-1">
                                        @if($student->alumniProfile && $student->alumniProfile->phone_number)
                                            <div class="flex items-center gap-2 font-bold text-emerald-600">
                                                <i class="ph-fill ph-whatsapp-logo text-emerald-500 text-sm"></i> {{ $student->alumniProfile->phone_number }}
                                            </div>
                                        @else
                                            <div class="text-slate-400">-</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- TOMBOL LIHAT BUKU INDUK --}}
                                        <a href="{{ route('students.show', $student->id) }}" target="_blank" class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-emerald-500 hover:border-emerald-500 hover:text-white transition-all shadow-sm" title="Lihat Arsip Buku Induk">
                                            <i class="ph-bold ph-book-open-text text-lg"></i>
                                        </a>

                                        {{-- TOMBOL DETAIL ALUMNI --}}
                                        <a href="{{ route('admin.alumni.show', $student->id) }}" class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-elevate-primary hover:border-elevate-primary hover:text-white transition-all shadow-sm" title="Lihat Detail Alumni">
                                            <i class="ph-bold ph-eye text-lg"></i>
                                        </a>
                                        
                                        {{-- TOMBOL EDIT ALUMNI --}}
                                        <a href="{{ route('admin.alumni.edit', $student->id) }}" class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-amber-500 hover:border-amber-500 hover:text-white transition-all shadow-sm" title="Edit Data Tracer Study">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                            <i class="ph-duotone ph-users-three text-4xl text-slate-300"></i>
                                        </div>
                                        <span class="font-bold text-slate-500 text-base">Belum ada data alumni yang ditemukan.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-slate-100 bg-slate-50/30">
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
                    confirmButtonColor: '#3b5889',
                    confirmButtonText: 'Tutup',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2.5rem] shadow-xl border border-slate-100'
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
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2.5rem] shadow-xl border border-slate-100'
                    }
                });
            @endif
        });
    </script>
</x-app-layout>