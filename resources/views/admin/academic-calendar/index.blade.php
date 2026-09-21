<x-app-layout>
    {{-- X-DATA CONTEXT: Alpine.js --}}
    <div x-data="{ 
        showEditModal: false, 
        editForm: { id: '', title: '', type: '', start_date: '', end_date: '', is_all_day: false, description: '' },
        openEdit(event) {
            this.editForm.id = event.id;
            this.editForm.title = event.title;
            this.editForm.type = event.type;
            this.editForm.start_date = event.start_date;
            this.editForm.end_date = event.end_date;
            this.editForm.is_all_day = event.is_all_day;
            this.editForm.description = event.description;
            this.showEditModal = true;
        }
    }" class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] relative overflow-hidden min-h-screen">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl absolute inset-0"></div>

        {{-- HERO SECTION (ELEVATED DARK GLASS THEME) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-8 sm:p-10 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none border-t border-l border-white/30"></div>
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-sky-400/20 rounded-full blur-3xl pointer-events-none group-hover:bg-sky-400/30 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- Text Content --}}
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm mb-4">
                            <i class="ph-fill ph-calendar text-sky-400"></i> Manajemen Agenda
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Kalender Pendidikan
                        </h1>
                        <p class="text-slate-300 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Kelola jadwal kegiatan, masa ujian, dan hari libur sekolah untuk sinkronisasi dengan seluruh entitas akademik.
                        </p>
                    </div>

                    {{-- Stats --}}
                    <div class="flex gap-4">
                        <div class="bg-slate-900/80 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/10 min-w-[140px] text-center md:text-left hover:bg-slate-800/90 transition-colors shadow-xl">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-sky-400">
                                <i class="ph-duotone ph-list-numbers text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300">Total Agenda</span>
                            </div>
                            <span class="block text-3xl font-black text-white tracking-tight">{{ $events->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Alert Sukses --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-2xl flex items-center justify-between shadow-xl backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-500/20 rounded-full text-emerald-400 border border-emerald-500/30">
                        <i class="ph-bold ph-check-circle text-xl"></i>
                    </div>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-200 p-1 rounded-md hover:bg-emerald-500/20 transition"><i class="ph-bold ph-x"></i></button>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- FORM INPUT (KIRI) --}}
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden sticky top-6 backdrop-blur-xl group/form hover:border-white/20 transition-all duration-300">
                        
                        {{-- Card Header --}}
                        <div class="bg-[#021124]/80 p-8 text-white relative overflow-hidden border-b border-white/10">
                            <div class="absolute -right-6 -top-6 text-sky-400/10 text-9xl pointer-events-none">
                                <i class="ph-fill ph-calendar-plus"></i>
                            </div>
                            <h3 class="text-xl font-black relative z-10">Tambah Agenda</h3>
                            <p class="text-slate-400 text-sm font-medium relative z-10 mt-1">Buat jadwal kegiatan baru.</p>
                        </div>

                        <div class="p-8">
                            <form action="{{ route('admin.academic-calendar.store') }}" method="POST" class="space-y-5">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Nama Agenda <span class="text-rose-400">*</span></label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-sky-400 transition-colors"></i>
                                        <input type="text" name="title" required placeholder="Contoh: Libur Semester" 
                                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all text-sm font-bold text-white outline-none shadow-sm placeholder:font-normal placeholder:text-slate-500">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Jenis Agenda <span class="text-rose-400">*</span></label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-sky-400 transition-colors"></i>
                                        <select name="type" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all text-sm font-bold text-white outline-none appearance-none cursor-pointer shadow-sm [color-scheme:dark]">
                                            <option value="kegiatan" class="bg-slate-900 text-white">Kegiatan Sekolah (Biru)</option>
                                            <option value="ujian" class="bg-slate-900 text-white">Ujian / Assesmen (Peach)</option>
                                            <option value="libur" class="bg-slate-900 text-white">Libur Sekolah (Merah)</option>
                                            <option value="nasional" class="bg-slate-900 text-white">Libur Nasional (Merah)</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Tgl Mulai <span class="text-rose-400">*</span></label>
                                        <input type="date" name="start_date" required 
                                               class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all text-sm font-bold text-white outline-none shadow-sm cursor-pointer [color-scheme:dark]">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Tgl Selesai</label>
                                        <input type="date" name="end_date" 
                                               class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all text-sm font-bold text-white outline-none shadow-sm cursor-pointer [color-scheme:dark]">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="flex items-center gap-3 p-4 border border-white/10 bg-slate-900/60 hover:bg-slate-900 rounded-2xl cursor-pointer hover:border-sky-400/40 transition-colors shadow-sm">
                                        <input type="checkbox" name="is_all_day" id="is_all_day" value="1" checked 
                                               class="w-5 h-5 text-sky-500 rounded focus:ring-sky-400 border-white/20 bg-slate-800 cursor-pointer">
                                        <div>
                                            <span class="block text-sm font-bold text-white">Seharian Penuh</span>
                                            <span class="block text-xs text-slate-400 mt-0.5">Agenda ini memakan waktu satu hari penuh.</span>
                                        </div>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Keterangan (Opsional)</label>
                                    <textarea name="description" rows="3" placeholder="Deskripsi tambahan..." 
                                              class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all text-sm font-medium text-white placeholder-slate-500 outline-none shadow-sm resize-none custom-scrollbar"></textarea>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-[#0d52a1] via-[#0d52a1] to-sky-600 hover:from-sky-600 hover:to-[#0d52a1] text-white rounded-2xl font-bold transition-all shadow-xl shadow-sky-950/40 flex justify-center items-center gap-2 transform active:scale-95 border border-sky-400/30">
                                        <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Agenda
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- DAFTAR AGENDA (KANAN) --}}
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden flex flex-col h-full min-h-[600px] backdrop-blur-xl">
                        <div class="p-8 border-b border-white/10 bg-[#021124]/80 flex justify-between items-center text-white">
                            <h2 class="text-lg font-black flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes text-sky-400"></i> Daftar Agenda
                            </h2>
                            <span class="bg-slate-900/80 border border-white/10 text-xs font-black px-3.5 py-1.5 rounded-xl text-sky-300 shadow-sm">
                                {{ $events->count() }} Data
                            </span>
                        </div>
                        
                        <div class="p-6 md:p-8 space-y-4 overflow-y-auto custom-scrollbar flex-1">
                            @forelse($events as $event)
                                @php
                                    $isPast = \Carbon\Carbon::parse($event->start_date)->isPast();
                                    // Setup Warna Badge & Icon Elevated Dark
                                    $badgeColor = 'bg-sky-500/10 text-sky-300 border-sky-400/30';
                                    $iconColor = 'bg-slate-900 text-sky-400 border-sky-400/30';
                                    $icon = 'ph-calendar-check';
                                    
                                    if($event->type == 'ujian') {
                                        $badgeColor = 'bg-amber-500/10 text-amber-300 border-amber-400/30';
                                        $iconColor = 'bg-slate-900 text-amber-400 border-amber-400/30';
                                        $icon = 'ph-pencil-simple';
                                    } elseif(in_array($event->type, ['libur', 'nasional'])) {
                                        $badgeColor = 'bg-rose-500/10 text-rose-300 border-rose-500/30';
                                        $iconColor = 'bg-slate-900 text-rose-400 border-rose-500/30';
                                        $icon = 'ph-tent';
                                    }
                                @endphp
                                <div class="bg-slate-900/70 p-6 rounded-[2rem] shadow-lg border {{ $isPast ? 'border-white/5 opacity-60' : 'border-white/10 hover:shadow-2xl hover:border-sky-400/40' }} flex flex-col sm:flex-row gap-5 items-start sm:items-center justify-between group transition-all duration-300">
                                    <div class="flex items-start gap-5">
                                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shrink-0 {{ $iconColor }} border shadow-md">
                                            <i class="ph-duotone {{ $icon }}"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg border {{ $badgeColor }} shadow-sm">
                                                    {{ $event->type }}
                                                </span>
                                                @if($isPast)
                                                    <span class="text-[10px] font-bold text-slate-400 px-2 py-1 bg-slate-900 rounded-lg border border-white/10">Sudah Berlalu</span>
                                                @endif
                                            </div>
                                            <h4 class="font-black text-white text-lg leading-tight mb-1.5 group-hover:text-sky-300 transition-colors">{{ $event->title }}</h4>
                                            <p class="text-sm font-semibold text-slate-400 flex items-center gap-1.5">
                                                <i class="ph-bold ph-clock text-sky-400"></i>
                                                {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }}
                                                @if($event->end_date)
                                                    - {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('d F Y') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-4 sm:mt-0 w-full sm:w-auto border-t sm:border-0 border-white/10 pt-4 sm:pt-0 justify-end opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                        {{-- TOMBOL EDIT --}}
                                        <button type="button" @click="openEdit({
                                            id: '{{ $event->id }}',
                                            title: '{{ addslashes($event->title) }}',
                                            type: '{{ $event->type }}',
                                            start_date: '{{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') }}',
                                            end_date: '{{ $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') : '' }}',
                                            is_all_day: {{ $event->is_all_day ? 'true' : 'false' }},
                                            description: '{{ addslashes($event->description ?? '') }}'
                                        })" class="w-10 h-10 rounded-xl bg-slate-800/80 border border-white/10 text-slate-300 hover:text-white hover:bg-slate-700 flex items-center justify-center transition-all shadow-sm">
                                            <i class="ph-bold ph-pencil-simple text-lg text-sky-400"></i>
                                        </button>

                                        {{-- TOMBOL HAPUS --}}
                                        <form action="{{ route('admin.academic-calendar.destroy', $event->id) }}" method="POST" id="delete-form-{{ $event->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('{{ $event->id }}', '{{ addslashes($event->title) }}')" 
                                                    class="w-10 h-10 rounded-xl bg-slate-800/80 border border-white/10 text-slate-300 hover:text-rose-300 hover:bg-rose-500/20 transition-all shadow-sm">
                                                <i class="ph-bold ph-trash text-lg text-rose-400"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-20 bg-slate-900/50 rounded-[2.5rem] border-2 border-dashed border-white/10 shadow-sm mt-6">
                                    <div class="w-24 h-24 bg-sky-500/10 border border-sky-400/20 rounded-full flex items-center justify-center mx-auto mb-6 text-sky-400 shadow-inner">
                                        <i class="ph-duotone ph-calendar-blank text-5xl"></i>
                                    </div>
                                    <h3 class="text-xl font-black text-white mb-2">Belum Ada Agenda</h3>
                                    <p class="text-slate-400 text-sm max-w-xs mx-auto">Silakan tambahkan agenda pertama Anda melalui form di samping.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT (Alpine.js) --}}
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                
                {{-- Backdrop Blur --}}
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-950/80 backdrop-blur-md" aria-hidden="true" @click="showEditModal = false"></div>

                <div x-show="showEditModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative inline-block w-full max-w-lg overflow-hidden text-left align-middle transition-all transform bg-[#021124] rounded-[2.5rem] shadow-2xl sm:my-8 border border-white/10 text-white">
                    
                    {{-- Modal Header --}}
                    <div class="bg-[#031d3d] p-6 flex justify-between items-center border-b border-white/10 text-white">
                        <h3 class="text-lg font-black flex items-center gap-2">
                            <i class="ph-bold ph-pencil-simple text-sky-400 text-xl"></i> Edit Agenda
                        </h3>
                        <button type="button" @click="showEditModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-slate-400 hover:text-white transition-colors border border-white/10">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <form :action="`/admin/academic-calendar/${editForm.id}`" method="POST" class="p-8 space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Nama Agenda <span class="text-rose-400">*</span></label>
                            <div class="relative">
                                <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="title" x-model="editForm.title" required 
                                       class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all text-sm font-bold text-white outline-none shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Jenis Agenda <span class="text-rose-400">*</span></label>
                            <div class="relative">
                                <select name="type" x-model="editForm.type" required class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all text-sm font-bold text-white outline-none appearance-none cursor-pointer shadow-sm [color-scheme:dark]">
                                    <option value="kegiatan" class="bg-slate-900 text-white">Kegiatan Sekolah (Biru)</option>
                                    <option value="ujian" class="bg-slate-900 text-white">Ujian / Assesmen (Peach)</option>
                                    <option value="libur" class="bg-slate-900 text-white">Libur Sekolah (Merah)</option>
                                    <option value="nasional" class="bg-slate-900 text-white">Libur Nasional (Merah)</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Tgl Mulai <span class="text-rose-400">*</span></label>
                                <input type="date" name="start_date" x-model="editForm.start_date" required 
                                       class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all text-sm font-bold text-white outline-none shadow-sm cursor-pointer [color-scheme:dark]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Tgl Selesai</label>
                                <input type="date" name="end_date" x-model="editForm.end_date" 
                                       class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all text-sm font-bold text-white outline-none shadow-sm cursor-pointer [color-scheme:dark]">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="flex items-center gap-3 p-4 border border-white/10 bg-slate-900/60 hover:bg-slate-900 rounded-2xl cursor-pointer hover:border-sky-400/40 transition-colors shadow-sm">
                                <input type="checkbox" name="is_all_day" id="edit_is_all_day" value="1" x-model="editForm.is_all_day"
                                       class="w-5 h-5 text-sky-500 rounded focus:ring-sky-400 border-white/20 bg-slate-800 cursor-pointer">
                                <div>
                                    <span class="block text-sm font-bold text-white">Seharian Penuh</span>
                                    <span class="block text-xs text-slate-400 mt-0.5">Agenda ini memakan waktu satu hari penuh.</span>
                                </div>
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Keterangan Tambahan</label>
                            <textarea name="description" x-model="editForm.description" rows="3" 
                                      class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-all text-sm font-medium text-white placeholder-slate-500 outline-none shadow-sm resize-none custom-scrollbar"></textarea>
                        </div>

                        {{-- Modal Footer Buttons --}}
                        <div class="pt-6 mt-6 border-t border-white/10 flex gap-3">
                            <button type="button" @click="showEditModal = false" class="flex-1 py-3.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-2xl font-bold transition-colors text-sm border border-white/10">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 py-3.5 bg-gradient-to-r from-[#0d52a1] via-[#0d52a1] to-sky-600 hover:from-sky-600 hover:to-[#0d52a1] text-white rounded-2xl font-bold transition-all shadow-xl shadow-sky-950/40 flex justify-center items-center gap-2 text-sm transform active:scale-95 border border-sky-400/30">
                                <i class="ph-bold ph-check text-lg"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(id, title) {
            Swal.fire({
                title: 'Hapus Agenda?',
                text: `Yakin ingin menghapus agenda "${title}" dari kalender pendidikan?`,
                icon: 'warning',
                showCancelButton: true,
                background: '#021124',
                color: '#fff',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3.5 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-950/40',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3.5 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-2 border border-white/10'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form-' + id);
                    if (form) form.submit();
                }
            });
        }
    </script>
</x-app-layout>