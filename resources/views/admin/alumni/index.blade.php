<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO HEADER MICROSOFT ELEVATE THEME --}}
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                {{-- Abstract Shapes Ornaments --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/50 border border-white/60 text-elevate-dark text-[10px] font-bold uppercase tracking-widest mb-3 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-users-three"></i> Database Alumni
                        </div>
                        <h1 class="text-3xl font-black text-elevate-dark tracking-tight mb-2">Tracer Study SMP</h1>
                        <p class="text-elevate-dark/80 text-sm max-w-xl font-medium">
                            Pantau sebaran lulusan ke SMA, SMK, MA, atau Pesantren.
                        </p>
                    </div>

                    {{-- Mini Stats --}}
                    <div class="flex gap-4">
                        <div class="text-center px-6 py-4 bg-white/60 rounded-2xl border border-white backdrop-blur-md shadow-sm">
                            <span class="block text-3xl font-black text-elevate-dark mb-1">
                                {{ isset($stats['total']) ? $stats['total'] : $alumni->total() }}
                            </span>
                            <span class="text-[10px] text-elevate-primary uppercase font-bold tracking-wider">Total Alumni</span>
                        </div>
                        <div class="text-center px-6 py-4 bg-white/60 rounded-2xl border border-white backdrop-blur-md shadow-sm hidden sm:block">
                            <span class="block text-3xl font-black text-elevate-primary mb-1">
                                {{ $stats['kuliah'] ?? 0 }}
                            </span>
                            <span class="text-[10px] text-elevate-dark/70 uppercase font-bold tracking-wider">Lanjut Sekolah</span>
                        </div>
                    </div>
                </div>
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
                                    @if($student->alumniProfile)
                                        @php $status = $student->alumniProfile->activity_status; @endphp
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide border
                                            {{ $status == 'SMA' ? 'bg-elevate-primary/10 text-elevate-primary border-elevate-primary/20' : '' }}
                                            {{ $status == 'SMK' ? 'bg-orange-50 text-orange-600 border-orange-100' : '' }}
                                            {{ $status == 'MA' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                            {{ $status == 'Pesantren' ? 'bg-teal-50 text-teal-600 border-teal-100' : '' }}
                                            {{ $status == 'Bekerja' ? 'bg-slate-100 text-slate-600 border-slate-200' : '' }}">
                                            {{ $status }}
                                        </span>
                                        <div class="text-xs text-elevate-dark font-bold mt-1.5 truncate max-w-[200px]" title="{{ $student->alumniProfile->campus_name ?? $student->alumniProfile->company_name }}">
                                            {{ $student->alumniProfile->campus_name ?? $student->alumniProfile->company_name ?? '-' }}
                                        </div>
                                    @else
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
                                        {{-- TOMBOL LIHAT BUKU INDUK (Data Riwayat Saat SMP) --}}
                                        <a href="{{ route('students.show', $student->id) }}" target="_blank" class="inline-flex w-9 h-9 items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-emerald-500 hover:border-emerald-500 hover:text-white transition-all shadow-sm" title="Lihat Arsip Buku Induk">
                                            <i class="ph-bold ph-book-open-text text-lg"></i>
                                        </a>

                                        {{-- TOMBOL DETAIL ALUMNI (Data Tracer Study Saat Ini) --}}
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
            // Cek Session Success
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3b5889', // elevate-primary
                    confirmButtonText: 'Tutup',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2.5rem] shadow-xl border border-slate-100'
                    }
                });
            @endif

            // Cek Session Error
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#e11d48', // Rose-500
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