{{-- Halaman ini adalah tampilan untuk resources/views/announcements/index.blade.php --}}
<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header Page --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                    <i class="ph-duotone ph-broadcast text-blue-600"></i> Pusat Informasi
                </h1>
                <p class="text-slate-500 mt-2 text-lg">
                    Kelola pengumuman, agenda kegiatan, dan notifikasi WhatsApp sekolah.
                </p>
            </div>
            
            {{-- Status Gateway (Hiasan) --}}
            <div class="hidden md:flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-slate-200 shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wide">WA Gateway Online</span>
            </div>
        </div>

        {{-- Pesan Flash --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="ph-bold ph-check-circle text-xl"></i>
                    </div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-md hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-400 hover:text-rose-600 p-1 rounded-md hover:bg-rose-100 transition"><i class="ph-bold ph-x"></i></button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            {{-- KOLOM KIRI (2/3): FORM INPUT --}}
            <div class="lg:col-span-2 space-y-8">
                
                <!-- CARD 1: PENGUMUMAN WEBSITE (BLUE) -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative group hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-600"></div>
                    <div class="p-6 md:p-8 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-blue-100">
                                <i class="ph-duotone ph-megaphone"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Pengumuman Website</h3>
                                <p class="text-sm text-slate-500">Tampilkan informasi penting di Halaman Depan.</p>
                            </div>
                        </div>
                        <form action="{{ route('announcements.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Judul Pengumuman</label>
                                <input type="text" name="title" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-800 py-3 transition-colors placeholder:font-normal" placeholder="Contoh: Jadwal Libur Awal Ramadhan" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Isi Konten</label>
                                <textarea name="content" rows="4" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm p-4 text-slate-700" placeholder="Tulis detail pengumuman di sini..." required></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="py-2.5 px-6 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 text-sm flex items-center gap-2 transform active:scale-95">
                                    <i class="ph-bold ph-paper-plane-right"></i>
                                    Publikasikan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- CARD 2: AGENDA KEGIATAN (PURPLE) -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative group hover:shadow-lg hover:shadow-purple-500/5 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-purple-600"></div>
                    <div class="p-6 md:p-8 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-purple-100">
                                <i class="ph-duotone ph-calendar-plus"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Agenda Kegiatan</h3>
                                <p class="text-sm text-slate-500">Jadwalkan event mendatang di Kalender Sekolah.</p>
                            </div>
                        </div>

                        <form action="{{ route('agendas.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-5">
                            @csrf
                            <div class="md:col-span-5">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Kegiatan</label>
                                <input type="text" name="title" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-800 py-3 placeholder:font-normal" placeholder="Contoh: Ujian Tengah Semester" required>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tanggal</label>
                                <input type="date" name="event_date" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 font-medium text-slate-800 py-3" required>
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Lokasi</label>
                                <div class="flex gap-2">
                                    <input type="text" name="location" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 text-sm py-3" placeholder="Contoh: Aula Sekolah">
                                    <button type="submit" class="p-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition shadow-lg shadow-purple-500/20 active:scale-95">
                                        <i class="ph-bold ph-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- LIST AGENDA AKTIF -->
                        <div class="mt-8 pt-6 border-t border-dashed border-slate-200">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes"></i> Agenda Mendatang
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @forelse($agendas as $agenda)
                                    <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50 hover:bg-white hover:border-purple-200 hover:shadow-sm transition-all group">
                                        <div class="flex items-center gap-3">
                                            <div class="text-center bg-white p-2 rounded-lg border border-slate-100 shadow-sm min-w-[50px]">
                                                <span class="block text-[9px] text-slate-400 font-bold uppercase">{{ $agenda->event_date->format('M') }}</span>
                                                <span class="block text-lg font-black text-purple-600 leading-none">{{ $agenda->event_date->format('d') }}</span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-700 line-clamp-1" title="{{ $agenda->title }}">{{ $agenda->title }}</p>
                                                <div class="flex items-center gap-1 text-[10px] text-slate-500 mt-0.5">
                                                    <i class="ph-fill ph-map-pin text-purple-400"></i>
                                                    {{ $agenda->location ?? 'Sekolah' }}
                                                </div>
                                            </div>
                                        </div>
                                        <form action="{{ route('agendas.destroy', $agenda->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-white hover:bg-rose-500 transition-all opacity-0 group-hover:opacity-100" onclick="return confirm('Hapus agenda ini?')" title="Hapus Agenda">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="col-span-2 text-center py-4 border border-dashed border-slate-200 rounded-xl">
                                        <p class="text-xs text-slate-400 italic">Belum ada agenda mendatang.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: BROADCAST WA (EMERALD) - DENGAN ALPINE JS -->
                <div class="bg-gradient-to-br from-emerald-600 to-green-700 rounded-3xl shadow-lg shadow-emerald-500/20 text-white overflow-hidden relative group">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>

                    <div class="p-6 md:p-8 relative z-10" x-data="{ target: 'class', message: '' }">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm text-white rounded-2xl flex items-center justify-center text-2xl shadow-inner border border-white/20">
                                <i class="ph-fill ph-whatsapp-logo"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-white">Broadcast WhatsApp</h3>
                                <p class="text-sm text-emerald-100">Kirim pesan massal ke orang tua/siswa.</p>
                            </div>
                        </div>

                        <form action="{{ route('announcements.send') }}" method="POST" class="space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-emerald-100 uppercase tracking-wider mb-1.5">Target Penerima</label>
                                    <select name="target_type" x-model="target" class="w-full rounded-xl border-emerald-500/50 bg-emerald-800/50 focus:bg-emerald-900 focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 font-bold text-white transition-colors cursor-pointer">
                                        <option value="class">Per Kelas (Spesifik)</option>
                                        <option value="student">Per Siswa (Personal)</option>
                                        <option value="all">Semua Siswa (Broadcast Akbar)</option>
                                    </select>
                                </div>
                                
                                {{-- Dropdown Dinamis dengan Alpine x-show --}}
                                <div x-show="target === 'class'" x-transition>
                                    <label class="block text-xs font-bold text-emerald-100 uppercase tracking-wider mb-1.5">Pilih Kelas</label>
                                    <select name="class_id" class="w-full rounded-xl border-emerald-500/50 bg-white text-slate-800 focus:bg-white focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 font-bold transition-colors">
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="target === 'student'" style="display: none;" x-transition>
                                    <label class="block text-xs font-bold text-emerald-100 uppercase tracking-wider mb-1.5">Pilih Siswa</label>
                                    <select name="student_id" class="w-full rounded-xl border-emerald-500/50 bg-white text-slate-800 focus:bg-white focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 font-bold transition-colors">
                                        <option value="">-- Cari Nama Siswa --</option>
                                        @if(isset($students))
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->schoolClass->name ?? '-' }})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-emerald-100 uppercase tracking-wider mb-1.5">Template Cepat</label>
                                <select @change="message = $event.target.value" class="w-full rounded-xl border-emerald-500/50 bg-emerald-800/50 focus:bg-emerald-900 focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 font-medium text-emerald-100 transition-colors cursor-pointer">
                                    <option value="">-- Pilih Template Pesan --</option>
                                    @foreach($templates as $key => $val)
                                        <option value="{{ $val }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-emerald-100 uppercase tracking-wider mb-1.5">Isi Pesan</label>
                                <textarea name="message" x-model="message" rows="4" class="w-full rounded-xl border-emerald-500/50 bg-white/10 focus:bg-white/20 focus:border-emerald-300 focus:ring-emerald-300 text-sm p-4 text-white placeholder-emerald-200/50 backdrop-blur-sm" placeholder="Halo Bapak/Ibu, kami menginformasikan bahwa..." required></textarea>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" onclick="return confirm('Yakin ingin mengirim pesan massal ini?')" class="py-3 px-6 bg-white text-emerald-700 font-black rounded-xl hover:bg-emerald-50 transition-all shadow-lg text-sm flex items-center gap-2 transform active:scale-95">
                                    <i class="ph-bold ph-paper-plane-tilt"></i>
                                    Kirim Pesan Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN (1/3): FEED PENGUMUMAN --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-[0_10px_30px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden h-full flex flex-col sticky top-24">
                    <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-white z-10">
                        <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <i class="ph-duotone ph-newspaper-clipping text-blue-500"></i> Feed Publik
                        </h3>
                        <span class="bg-slate-100 text-[10px] font-bold px-2 py-1 rounded-full text-slate-500 border border-slate-200">{{ $announcements->count() }} Post</span>
                    </div>
                    
                    <div class="p-0 overflow-y-auto flex-1 max-h-[calc(100vh-200px)] custom-scrollbar">
                        <div class="divide-y divide-slate-50">
                            @forelse($announcements as $announce)
                                <div class="p-5 hover:bg-slate-50/80 transition-all group relative">
                                    <!-- Header Post -->
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-[9px] font-bold text-white shadow-sm ring-2 ring-white">
                                                {{ substr($announce->author->name ?? 'A', 0, 1) }}
                                            </div>
                                            <span class="text-[11px] font-bold text-slate-700">{{ $announce->author->name ?? 'Admin' }}</span>
                                        </div>
                                        <span class="text-[10px] font-medium text-slate-400 flex items-center gap-1 bg-white px-2 py-0.5 rounded-full border border-slate-100">
                                            {{ $announce->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <!-- Content -->
                                    <div class="pl-8">
                                        <h4 class="font-bold text-slate-800 mb-1.5 text-sm leading-tight group-hover:text-blue-600 transition-colors">{{ $announce->title }}</h4>
                                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed mb-3">
                                            {{ Str::limit(strip_tags($announce->content), 120) }}
                                        </p>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">
                                                    <i class="ph-bold ph-calendar-blank"></i> {{ $announce->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                            
                                            <form action="{{ route('announcements.destroy', $announce->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-300 hover:text-rose-500 hover:bg-rose-50 transition-all opacity-0 group-hover:opacity-100" onclick="return confirm('Hapus pengumuman ini?')" title="Hapus Post">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-16 px-6">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                        <i class="ph-duotone ph-article text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-600">Belum ada pengumuman.</p>
                                    <p class="text-xs text-slate-400 mt-1">Buat pengumuman pertama Anda di form sebelah kiri.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>