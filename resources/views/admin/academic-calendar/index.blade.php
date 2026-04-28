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
    }" class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION (ELEVATED THEME) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">
            <div class="relative rounded-[2.5rem] bg-elevate-gradient-main p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/40 rounded-full blur-3xl pointer-events-none group-hover:bg-white/60 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- Text Content --}}
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-soft border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-calendar"></i> Manajemen Agenda
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Kalender Pendidikan
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-semibold leading-relaxed max-w-lg">
                            Kelola jadwal kegiatan, masa ujian, dan hari libur sekolah untuk sinkronisasi dengan seluruh entitas akademik.
                        </p>
                    </div>

                    {{-- Stats --}}
                    <div class="flex gap-4">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/80 min-w-[140px] text-center md:text-left hover:bg-white transition-colors shadow-sm">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-elevate-primary">
                                <i class="ph-duotone ph-list-numbers text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Agenda</span>
                            </div>
                            <span class="block text-3xl font-black text-elevate-dark tracking-tight">{{ $events->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Alert Sukses --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="ph-bold ph-check-circle text-xl"></i>
                    </div>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-md hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- FORM INPUT (KIRI) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-6 group/form hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300">
                        
                        {{-- Card Header --}}
                        <div class="bg-elevate-gradient-card p-8 text-elevate-dark relative overflow-hidden border-b border-slate-100">
                            <div class="absolute -right-6 -top-6 text-elevate-primary/5 text-9xl pointer-events-none">
                                <i class="ph-fill ph-calendar-plus"></i>
                            </div>
                            <h3 class="text-xl font-black relative z-10">Tambah Agenda</h3>
                            <p class="text-elevate-dark/60 text-sm font-medium relative z-10 mt-1">Buat jadwal kegiatan baru.</p>
                        </div>

                        <div class="p-8">
                            <form action="{{ route('admin.academic-calendar.store') }}" method="POST" class="space-y-5">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Nama Agenda <span class="text-rose-500">*</span></label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                        <input type="text" name="title" required placeholder="Contoh: Libur Semester" 
                                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 transition-all text-sm font-bold text-elevate-dark outline-none shadow-sm placeholder:font-medium placeholder:text-slate-400">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Jenis Agenda <span class="text-rose-500">*</span></label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                        <select name="type" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 transition-all text-sm font-bold text-elevate-dark outline-none appearance-none cursor-pointer shadow-sm">
                                            <option value="kegiatan">Kegiatan Sekolah (Biru)</option>
                                            <option value="ujian">Ujian / Assesmen (Peach)</option>
                                            <option value="libur">Libur Sekolah (Merah)</option>
                                            <option value="nasional">Libur Nasional (Merah)</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tgl Mulai <span class="text-rose-500">*</span></label>
                                        <input type="date" name="start_date" required 
                                               class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 transition-all text-sm font-bold text-elevate-dark outline-none shadow-sm cursor-pointer">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tgl Selesai</label>
                                        <input type="date" name="end_date" 
                                               class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 transition-all text-sm font-bold text-elevate-dark outline-none shadow-sm cursor-pointer">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="flex items-center gap-3 p-4 border border-slate-200 bg-white hover:bg-elevate-soft rounded-2xl cursor-pointer hover:border-elevate-accent transition-colors shadow-sm">
                                        <input type="checkbox" name="is_all_day" id="is_all_day" value="1" checked 
                                               class="w-5 h-5 text-elevate-primary rounded focus:ring-elevate-accent border-slate-300 cursor-pointer">
                                        <div>
                                            <span class="block text-sm font-bold text-elevate-dark">Seharian Penuh</span>
                                            <span class="block text-xs text-elevate-dark/60 mt-0.5">Agenda ini memakan waktu satu hari penuh.</span>
                                        </div>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Keterangan (Opsional)</label>
                                    <textarea name="description" rows="3" placeholder="Deskripsi tambahan..." 
                                              class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 transition-all text-sm font-medium text-elevate-dark outline-none shadow-sm resize-none custom-scrollbar"></textarea>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="w-full py-4 bg-elevate-dark hover:bg-elevate-primary text-white rounded-2xl font-bold transition-all shadow-lg shadow-elevate-dark/30 flex justify-center items-center gap-2 transform active:scale-95 border border-transparent">
                                        <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Agenda
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- DAFTAR AGENDA (KANAN) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full min-h-[600px]">
                        <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h2 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Daftar Agenda
                            </h2>
                            <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-xl text-elevate-primary shadow-sm">
                                {{ $events->count() }} Data
                            </span>
                        </div>
                        
                        <div class="p-6 md:p-8 space-y-4 overflow-y-auto custom-scrollbar flex-1 bg-slate-50/30">
                            @forelse($events as $event)
                                @php
                                    $isPast = \Carbon\Carbon::parse($event->start_date)->isPast();
                                    // Setup Warna Badge & Icon Elevated
                                    $badgeColor = 'bg-elevate-soft text-elevate-primary border-elevate-accent/30';
                                    $iconColor = 'bg-white text-elevate-primary border-elevate-accent/30';
                                    $icon = 'ph-calendar-check';
                                    
                                    if($event->type == 'ujian') {
                                        $badgeColor = 'bg-elevate-peach-light/40 text-elevate-peach-dark border-elevate-peach/30';
                                        $iconColor = 'bg-white text-elevate-peach-dark border-elevate-peach/30';
                                        $icon = 'ph-pencil-simple';
                                    } elseif(in_array($event->type, ['libur', 'nasional'])) {
                                        $badgeColor = 'bg-rose-50 text-rose-600 border-rose-200';
                                        $iconColor = 'bg-white text-rose-600 border-rose-200';
                                        $icon = 'ph-tent';
                                    }
                                @endphp
                                <div class="bg-elevate-gradient-card p-6 rounded-[2rem] shadow-sm border {{ $isPast ? 'border-slate-100 opacity-60' : 'border-slate-200 hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/50' }} flex flex-col sm:flex-row gap-5 items-start sm:items-center justify-between group transition-all duration-300">
                                    <div class="flex items-start gap-5">
                                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shrink-0 {{ $iconColor }} border shadow-sm">
                                            <i class="ph-duotone {{ $icon }}"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg border {{ $badgeColor }} shadow-sm">
                                                    {{ $event->type }}
                                                </span>
                                                @if($isPast)
                                                    <span class="text-[10px] font-bold text-slate-400 px-2 py-1 bg-slate-50 rounded-lg border border-slate-200">Sudah Berlalu</span>
                                                @endif
                                            </div>
                                            <h4 class="font-black text-elevate-dark text-lg leading-tight mb-1.5 group-hover:text-elevate-primary transition-colors">{{ $event->title }}</h4>
                                            <p class="text-sm font-semibold text-slate-500 flex items-center gap-1.5">
                                                <i class="ph-bold ph-clock text-elevate-accent"></i>
                                                {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }}
                                                @if($event->end_date)
                                                    - {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('d F Y') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 mt-4 sm:mt-0 w-full sm:w-auto border-t sm:border-0 border-slate-100 pt-4 sm:pt-0 justify-end opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                        {{-- TOMBOL EDIT --}}
                                        <button type="button" @click="openEdit({
                                            id: '{{ $event->id }}',
                                            title: '{{ addslashes($event->title) }}',
                                            type: '{{ $event->type }}',
                                            start_date: '{{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') }}',
                                            end_date: '{{ $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') : '' }}',
                                            is_all_day: {{ $event->is_all_day ? 'true' : 'false' }},
                                            description: '{{ addslashes($event->description ?? '') }}'
                                        })" class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-elevate-soft hover:text-elevate-primary hover:border-elevate-accent/50 transition-all shadow-sm">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </button>

                                        {{-- TOMBOL HAPUS --}}
                                        <form action="{{ route('admin.academic-calendar.destroy', $event->id) }}" method="POST" id="delete-form-{{ $event->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('{{ $event->id }}', '{{ addslashes($event->title) }}')" 
                                                    class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 shadow-sm mt-6">
                                    <div class="w-24 h-24 bg-elevate-peach-light/50 rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-peach-dark shadow-inner">
                                        <i class="ph-duotone ph-calendar-blank text-5xl"></i>
                                    </div>
                                    <h3 class="text-xl font-black text-elevate-dark mb-2">Belum Ada Agenda</h3>
                                    <p class="text-elevate-dark/60 text-sm max-w-xs mx-auto">Silakan tambahkan agenda pertama Anda melalui form di samping.</p>
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
                <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-elevate-dark/60 backdrop-blur-sm" aria-hidden="true" @click="showEditModal = false"></div>

                <div x-show="showEditModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative inline-block w-full max-w-lg overflow-hidden text-left align-middle transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 border border-slate-100">
                    
                    {{-- Modal Header --}}
                    <div class="bg-elevate-gradient-card p-6 flex justify-between items-center border-b border-slate-100 text-elevate-dark">
                        <h3 class="text-lg font-black flex items-center gap-2">
                            <i class="ph-bold ph-pencil-simple text-elevate-primary text-xl"></i> Edit Agenda
                        </h3>
                        <button type="button" @click="showEditModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-white text-slate-400 hover:text-elevate-dark shadow-sm transition-colors border border-slate-200">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <form :action="`/admin/academic-calendar/${editForm.id}`" method="POST" class="p-8 space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Nama Agenda <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="title" x-model="editForm.title" required 
                                       class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 transition-all text-sm font-bold text-elevate-dark outline-none shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Jenis Agenda <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="type" x-model="editForm.type" required class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 transition-all text-sm font-bold text-elevate-dark outline-none appearance-none cursor-pointer shadow-sm">
                                    <option value="kegiatan">Kegiatan Sekolah (Biru)</option>
                                    <option value="ujian">Ujian / Assesmen (Peach)</option>
                                    <option value="libur">Libur Sekolah (Merah)</option>
                                    <option value="nasional">Libur Nasional (Merah)</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tgl Mulai <span class="text-rose-500">*</span></label>
                                <input type="date" name="start_date" x-model="editForm.start_date" required 
                                       class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 transition-all text-sm font-bold text-elevate-dark outline-none shadow-sm cursor-pointer">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tgl Selesai</label>
                                <input type="date" name="end_date" x-model="editForm.end_date" 
                                       class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 transition-all text-sm font-bold text-elevate-dark outline-none shadow-sm cursor-pointer">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="flex items-center gap-3 p-4 border border-slate-200 bg-white hover:bg-elevate-soft rounded-2xl cursor-pointer hover:border-elevate-accent transition-colors shadow-sm">
                                <input type="checkbox" name="is_all_day" id="edit_is_all_day" value="1" x-model="editForm.is_all_day"
                                       class="w-5 h-5 text-elevate-primary rounded focus:ring-elevate-accent border-slate-300 cursor-pointer">
                                <div>
                                    <span class="block text-sm font-bold text-elevate-dark">Seharian Penuh</span>
                                    <span class="block text-xs text-elevate-dark/60 mt-0.5">Agenda ini memakan waktu satu hari penuh.</span>
                                </div>
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Keterangan Tambahan</label>
                            <textarea name="description" x-model="editForm.description" rows="3" 
                                      class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 transition-all text-sm font-medium text-elevate-dark outline-none shadow-sm resize-none custom-scrollbar"></textarea>
                        </div>

                        {{-- Modal Footer Buttons --}}
                        <div class="pt-6 mt-6 border-t border-slate-100 flex gap-3">
                            <button type="button" @click="showEditModal = false" class="flex-1 py-3.5 bg-slate-100 hover:bg-slate-200 text-elevate-dark/60 rounded-2xl font-bold transition-colors text-sm border border-transparent">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 py-3.5 bg-elevate-dark hover:bg-elevate-primary text-white rounded-2xl font-bold transition-all shadow-lg shadow-elevate-dark/30 flex justify-center items-center gap-2 text-sm transform active:scale-95 border border-transparent">
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
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3.5 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-600/30',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3.5 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
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