<x-app-layout>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20" 
         x-data="teachingEdit({ sessionId: {{ $session->id }} })">
         
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO HEADER ELEVATE --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-start justify-between gap-6">
                    <div>
                        <a href="{{ route('teaching.show', $session->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/60 border border-white/60 text-elevate-dark text-xs font-bold hover:bg-white transition-all mb-4 shadow-sm backdrop-blur-sm active:scale-95">
                            <i class="ph-bold ph-arrow-left"></i> Batal & Kembali
                        </a>
                        <h1 class="text-3xl md:text-4xl font-black text-elevate-dark tracking-tight">Edit Sesi Kelas</h1>
                        <p class="text-elevate-dark/80 font-semibold text-sm mt-2 max-w-lg leading-relaxed">Revisi jurnal mengajar dan perbaiki absensi siswa untuk sesi yang sudah ditutup.</p>
                    </div>
                    <div class="px-5 py-3 bg-[#FFEFD6] text-[#D83B01] rounded-xl border border-[#FFD8A8] font-bold text-xs flex items-center gap-2 shadow-sm shrink-0">
                        <span class="w-2 h-2 rounded-full bg-[#D83B01] animate-pulse"></span> Mode Edit
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                
                {{-- KOLOM KIRI: FORM JURNAL --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 p-6 md:p-8 h-full border border-slate-100">
                        <h3 class="font-black text-elevate-dark text-xl mb-6 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-elevate-soft text-elevate-primary flex items-center justify-center shadow-sm">
                                <i class="ph-bold ph-notebook"></i>
                            </div>
                            Jurnal Mengajar
                        </h3>
                        
                        {{-- Form Update Jurnal --}}
                        <form action="{{ route('teaching.update', $session->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <input type="hidden" name="redirect_to" value="edit">
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Topik / Materi</label>
                                    <input type="text" name="topic" value="{{ old('topic', $session->topic) }}" 
                                        class="w-full rounded-2xl border-slate-200 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark bg-elevate-soft transition-all py-3.5 px-4" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Catatan Kegiatan</label>
                                    <textarea name="activities" rows="4" 
                                        class="w-full rounded-2xl border-slate-200 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 text-sm text-elevate-dark font-medium bg-elevate-soft transition-all py-3.5 px-4">{{ old('activities', $session->activities) }}</textarea>
                                </div>
                                
                                {{-- Upload Foto Ulang --}}
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Update Foto</label>
                                    @if($session->photo_proof)
                                        <div class="mb-4 relative group rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
                                            <img src="{{ asset('storage/' . $session->photo_proof) }}" class="h-32 w-full object-cover">
                                            <div class="absolute inset-0 bg-elevate-dark/60 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-white text-xs font-bold gap-2">
                                                <i class="ph-bold ph-image text-lg"></i> Foto Saat Ini
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" name="photo_proof" accept="image/*" class="w-full text-xs font-semibold text-slate-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-primary/20 border border-slate-200 rounded-2xl bg-white cursor-pointer shadow-sm transition-all">
                                </div>

                                <button type="submit" class="w-full bg-elevate-dark text-white hover:bg-elevate-primary font-bold py-4 rounded-2xl shadow-lg shadow-elevate-dark/30 transition-all active:scale-95 flex items-center justify-center gap-2 border border-transparent mt-6">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Perubahan Jurnal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN: LIST ABSENSI (MANUAL FIX) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 flex flex-col h-full overflow-hidden border border-slate-100">
                        <div class="p-6 md:p-8 border-b border-slate-100 bg-elevate-gradient-card flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h3 class="font-black text-elevate-dark text-xl">Koreksi Absensi</h3>
                                <p class="text-sm text-elevate-dark/70 font-semibold mt-1">Ubah status kehadiran siswa melalui tombol di sebelah kanan.</p>
                            </div>
                            
                            {{-- Filter Cepat --}}
                            <div class="flex gap-2 shrink-0">
                                <span class="px-4 py-2 rounded-xl bg-[#FDE7E9] text-[#D13438] text-[10px] font-black border border-[#F4C3C9] uppercase tracking-widest shadow-sm">
                                    Alpha: {{ collect($attendances)->where('status', 'alpha')->count() }}
                                </span>
                            </div>
                        </div>

                        <div class="flex-1 p-6 md:p-8 overflow-y-auto max-h-[800px] custom-scrollbar bg-white">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($allStudents as $student)
                                    @php
                                        $att = $attendances[$student->id] ?? null;
                                        $status = $att ? $att->status : null; 
                                    @endphp

                                    <div class="flex items-center justify-between p-4 rounded-2xl border-2 border-slate-100 bg-white shadow-sm hover:border-elevate-accent/50 transition-colors group"
                                         id="row-{{ $student->id }}"
                                         x-data="{ currentStatus: '{{ $status }}' }">
                                        
                                        <div class="flex items-center gap-4 overflow-hidden">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-lg shrink-0 transition-colors shadow-sm"
                                                 :class="{
                                                    'bg-[#107C10] text-white': currentStatus === 'present',
                                                    'bg-[#D13438] text-white': currentStatus === 'alpha',
                                                    'bg-elevate-primary text-white': currentStatus === 'sick',
                                                    'bg-[#D83B01] text-white': currentStatus === 'permission',
                                                    'bg-slate-100 text-slate-400': !currentStatus
                                                 }">
                                                <i class="ph-bold" :class="{
                                                    'ph-check': currentStatus === 'present',
                                                    'ph-x': currentStatus === 'alpha',
                                                    'ph-thermometer': currentStatus === 'sick',
                                                    'ph-hand-waving': currentStatus === 'permission',
                                                    'ph-question': !currentStatus
                                                }"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-black text-elevate-dark text-sm leading-tight truncate">{{ $student->name }}</p>
                                                <p class="text-[10px] font-bold text-slate-500 tracking-wide mt-1">{{ $student->student_id }}</p>
                                            </div>
                                        </div>

                                        {{-- Dropdown Action --}}
                                        <div class="relative shrink-0" x-data="{ open: false }">
                                            <button @click="open = !open" @click.outside="open = false" 
                                                class="px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider border flex items-center gap-2 transition-all shadow-sm active:scale-95"
                                                :class="{
                                                    'bg-white border-slate-200 text-slate-600 hover:bg-slate-50': true,
                                                    'bg-[#FDE7E9] border-[#F4C3C9] text-[#D13438] hover:bg-[#F4C3C9]': currentStatus === 'alpha'
                                                }">
                                                <span x-text="currentStatus ? currentStatus : 'PILIH'"></span>
                                                <i class="ph-bold ph-caret-down text-sm"></i>
                                            </button>

                                            <div x-show="open" x-transition style="display: none;" class="absolute right-0 mt-2 w-44 bg-white rounded-2xl shadow-xl shadow-elevate-dark/20 border border-slate-100 z-50 p-2 overflow-hidden">
                                                <button @click="updateStatus({{ $student->id }}, 'Hadir'); currentStatus='Hadir'; open=false" class="w-full text-left px-4 py-3 text-xs font-bold text-[#107C10] hover:bg-[#DFF6DD] rounded-lg flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#107C10]"></span> Hadir</button>
                                                <button @click="updateStatus({{ $student->id }}, 'Sakit'); currentStatus='Sakit'; open=false" class="w-full text-left px-4 py-3 text-xs font-bold text-elevate-primary hover:bg-elevate-soft rounded-lg flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-elevate-primary"></span> Sakit</button>
                                                <button @click="updateStatus({{ $student->id }}, 'Izin'); currentStatus='Izin'; open=false" class="w-full text-left px-4 py-3 text-xs font-bold text-[#D83B01] hover:bg-[#FFEFD6] rounded-lg flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#D83B01]"></span> Izin</button>
                                                <button @click="updateStatus({{ $student->id }}, 'Alpha'); currentStatus='Alpha'; open=false" class="w-full text-left px-4 py-3 text-xs font-bold text-[#D13438] hover:bg-[#FDE7E9] rounded-lg flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#D13438]"></span> Alpha</button>
                                                <div class="border-t border-slate-100 my-1"></div>
                                                <button @click="updateStatus({{ $student->id }}, null); currentStatus=null; open=false" class="w-full text-left px-4 py-3 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-lg">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('teachingEdit', (config) => ({
                sessionId: config.sessionId,

                async updateStatus(studentId, status) {
                    try {
                        const response = await fetch('{{ route("teaching.manual") }}', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                            },
                            body: JSON.stringify({ session_id: this.sessionId, student_id: studentId, status: status })
                        });
                        const data = await response.json();
                        
                        if(data.status === 'success') {
                            const Toast = Swal.mixin({
                                toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true,
                                customClass: { popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans' },
                                didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                            })
                            Toast.fire({ icon: 'success', title: 'Status diperbarui' });
                        }
                    } catch (e) { 
                        Swal.fire({
                            title: 'Error', text: 'Gagal update status', icon: 'error',
                            customClass: { popup: 'rounded-[2rem] shadow-2xl font-sans border-0' }
                        }); 
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>