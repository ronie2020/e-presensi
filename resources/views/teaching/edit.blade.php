<x-app-layout>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    {{-- CUSTOM STYLES FLUENT --}}
    <style>
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-card:hover { box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108); transform: translateY(-2px); }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18); border: 1px solid rgba(0, 0, 0, 0.05); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    <div class="py-8 font-sans text-slate-800 pb-20" 
         x-data="teachingEdit({ 
            sessionId: {{ $session->id }}
         })">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO HEADER ELEVATE --}}
            <div class="relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 mb-8 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/30 rounded-full blur-[80px] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-start justify-between gap-6">
                    <div>
                        <a href="{{ route('teaching.show', $session->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/40 border border-white/50 text-[#2A3B52] text-xs font-bold hover:bg-white/60 transition mb-3 shadow-sm backdrop-blur-sm active:scale-95">
                            <i class="ph-bold ph-arrow-left"></i> Batal & Kembali
                        </a>
                        <h1 class="text-3xl font-black text-[#2A3B52] tracking-tight">Edit Sesi Kelas</h1>
                        <p class="text-[#2A3B52]/80 font-medium text-sm mt-1">Revisi jurnal dan absensi siswa untuk sesi yang sudah ditutup.</p>
                    </div>
                    <div class="px-4 py-2.5 bg-[#FFEFD6]/80 text-[#D83B01] rounded-xl border border-[#FFD8A8] font-bold text-xs flex items-center gap-2 animate-pulse shadow-sm backdrop-blur-sm">
                        <i class="ph-fill ph-pencil-simple"></i> Mode Edit Admin
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                
                {{-- KOLOM KIRI: FORM JURNAL --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-xl fluent-card p-6 h-full border border-slate-100">
                        <h3 class="font-bold text-[#2A3B52] text-lg mb-6 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] flex items-center justify-center shadow-sm">
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
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Topik / Materi</label>
                                    <input type="text" name="topic" value="{{ old('topic', $session->topic) }}" 
                                        class="w-full rounded-xl border-slate-200 focus:border-[#5295FF] focus:ring-[#5295FF] font-bold text-[#2A3B52] bg-slate-50 transition-colors" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Catatan Kegiatan</label>
                                    <textarea name="activities" rows="4" 
                                        class="w-full rounded-xl border-slate-200 focus:border-[#5295FF] focus:ring-[#5295FF] text-sm text-[#2A3B52] bg-slate-50 font-medium transition-colors">{{ old('activities', $session->activities) }}</textarea>
                                </div>
                                
                                {{-- Upload Foto Ulang --}}
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Update Foto (Opsional)</label>
                                    @if($session->photo_proof)
                                        <div class="mb-3 relative group rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                            <img src="{{ asset('storage/' . $session->photo_proof) }}" class="h-28 w-full object-cover">
                                            <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-white text-xs font-bold gap-1">
                                                <i class="ph-bold ph-image"></i> Foto Saat Ini
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" name="photo_proof" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:font-bold file:bg-[#F3F9FD] file:text-[#5295FF] hover:file:bg-[#E0F0FC] border border-slate-200 rounded-xl bg-white cursor-pointer">
                                </div>

                                <button type="submit" class="w-full bg-[#2A3B52] text-white hover:bg-[#182436] font-bold py-3.5 rounded-xl shadow-md transition-all active:scale-95 flex items-center justify-center gap-2 border border-transparent mt-4">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Perubahan Jurnal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN: LIST ABSENSI (MANUAL FIX) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl fluent-card flex flex-col h-full overflow-hidden border border-slate-100">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h3 class="font-bold text-[#2A3B52] text-lg">Koreksi Absensi</h3>
                                <p class="text-xs text-slate-500 font-medium mt-1">Klik tombol status untuk mengubah kehadiran.</p>
                            </div>
                            
                            {{-- Filter Cepat --}}
                            <div class="flex gap-2">
                                <span class="px-3 py-1.5 rounded-lg bg-[#FDE7E9] text-[#D13438] text-[10px] font-bold border border-[#F4C3C9] uppercase tracking-wider shadow-sm">
                                    Alpha: {{ collect($attendances)->where('status', 'alpha')->count() }}
                                </span>
                            </div>
                        </div>

                        <div class="flex-1 p-6 overflow-y-auto max-h-[800px] custom-scrollbar bg-slate-50/30">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($allStudents as $student)
                                    @php
                                        $att = $attendances[$student->id] ?? null;
                                        $status = $att ? $att->status : null; 
                                    @endphp

                                    <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-[#5295FF] transition-colors group"
                                         id="row-{{ $student->id }}"
                                         x-data="{ currentStatus: '{{ $status }}' }">
                                        
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg shrink-0 transition-colors border"
                                                 :class="{
                                                    'bg-[#107C10] text-white border-[#107C10]': currentStatus === 'present',
                                                    'bg-[#D13438] text-white border-[#D13438]': currentStatus === 'alpha',
                                                    'bg-[#5295FF] text-white border-[#5295FF]': currentStatus === 'sick',
                                                    'bg-[#D83B01] text-white border-[#D83B01]': currentStatus === 'permission',
                                                    'bg-slate-100 text-slate-400 border-slate-200': !currentStatus
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
                                                <p class="font-bold text-[#2A3B52] text-sm truncate">{{ $student->name }}</p>
                                                <p class="text-[10px] font-mono text-slate-500 tracking-wide mt-0.5">{{ $student->student_id }}</p>
                                            </div>
                                        </div>

                                        {{-- Dropdown Action --}}
                                        <div class="relative shrink-0" x-data="{ open: false }">
                                            <button @click="open = !open" @click.outside="open = false" 
                                                class="px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider border flex items-center gap-2 transition-all shadow-sm active:scale-95"
                                                :class="{
                                                    'bg-white border-slate-200 text-slate-600 hover:bg-slate-50': true,
                                                    'bg-[#FDE7E9] border-[#F4C3C9] text-[#D13438] hover:bg-[#F4C3C9]': currentStatus === 'alpha'
                                                }">
                                                <span x-text="currentStatus ? currentStatus : 'PILIH'"></span>
                                                <i class="ph-bold ph-caret-down text-sm"></i>
                                            </button>

                                            <div x-show="open" x-transition style="display: none;" class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-xl border border-slate-100 z-50 py-1.5 overflow-hidden">
                                                <button @click="updateStatus({{ $student->id }}, 'Hadir'); currentStatus='Hadir'; open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-[#107C10] hover:bg-[#DFF6DD] flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#107C10]"></span> Hadir</button>
                                                <button @click="updateStatus({{ $student->id }}, 'Sakit'); currentStatus='Sakit'; open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-[#5295FF] hover:bg-[#F3F9FD] flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#5295FF]"></span> Sakit</button>
                                                <button @click="updateStatus({{ $student->id }}, 'Izin'); currentStatus='Izin'; open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-[#D83B01] hover:bg-[#FFEFD6] flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#D83B01]"></span> Izin</button>
                                                <button @click="updateStatus({{ $student->id }}, 'Alpha'); currentStatus='Alpha'; open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-[#D13438] hover:bg-[#FDE7E9] flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#D13438]"></span> Alpha</button>
                                                <div class="border-t border-slate-100 my-1"></div>
                                                <button @click="updateStatus({{ $student->id }}, null); currentStatus=null; open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-500 hover:bg-slate-50">Reset</button>
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
                                customClass: { popup: 'rounded-xl border border-slate-200 shadow-md font-sans' },
                                didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                            })
                            Toast.fire({ icon: 'success', title: 'Status diperbarui' });
                        }
                    } catch (e) { 
                        Swal.fire({
                            title: 'Error', text: 'Gagal update status', icon: 'error',
                            customClass: { popup: 'rounded-xl fluent-modal font-sans border-0' }
                        }); 
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>