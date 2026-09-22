<x-app-layout>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden pb-20" 
         x-data="teachingEdit({ sessionId: {{ $session->id }} })">
         
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-r from-sky-600/20 via-blue-600/10 to-transparent pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO HEADER --}}
            <x-hero-section
                badge="{{ $session->timetable->studentClass->name ?? 'Kelas' }}"
                badgeIcon="ph-chalkboard-teacher"
                title="Edit Jurnal"
                titleHighlight="{{ $session->timetable->subject->name ?? 'Pelajaran' }}"
                description="Perbarui materi pembahasan, catatan jurnal kelas, kendala, atau data kehadiran siswa pada sesi ini."
                :chips="[
                    ['icon' => 'ph-calendar-check', 'label' => ($session->timetable->timeslot->name ?? 'Sesi Pelajaran')],
                    ['icon' => 'ph-clock', 'label' => \Carbon\Carbon::parse($session->timetable->timeslot->start_time ?? now())->format('H:i') . ' - ' . \Carbon\Carbon::parse($session->timetable->timeslot->end_time ?? now())->format('H:i')],
                    ['icon' => 'ph-pencil-simple', 'label' => 'Revisi Sesi']
                ]"
                heroIcon="ph-chalkboard-teacher"
                statusOrb="Mode Edit"
                statusColor="amber"
                ctaPrimaryText="Batal & Kembali"
                ctaPrimaryHref="{{ route('teaching.show', $session->id) }}"
                ctaPrimaryIcon="ph-arrow-left"
            />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                
                {{-- KOLOM KIRI: FORM JURNAL --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] shadow-2xl p-6 md:p-8 h-full border border-white/10 backdrop-blur-xl text-white">
                        <h3 class="font-black text-white text-xl mb-6 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-500/30 flex items-center justify-center shadow-sm">
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
                                    <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Topik / Materi Pembelajaran</label>
                                    <input type="text" name="topic" value="{{ old('topic', $session->topic) }}" 
                                        class="w-full rounded-2xl border border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white transition-all py-3.5 px-4 [color-scheme:dark]" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Catatan Kegiatan Guru</label>
                                    <textarea name="activities" rows="4" 
                                        class="w-full rounded-2xl border border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm text-white font-medium transition-all py-3.5 px-4">{{ old('activities', $session->activities) }}</textarea>
                                </div>
                                
                                {{-- Upload Foto Ulang --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Perbarui Bukti Foto</label>
                                    @if($session->photo_proof)
                                        <div class="mb-4 relative group rounded-2xl overflow-hidden border border-white/10 shadow-sm">
                                            <img src="{{ asset('storage/' . $session->photo_proof) }}" class="h-32 w-full object-cover">
                                            <div class="absolute inset-0 bg-[#021124]/80 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-white text-xs font-bold gap-2">
                                                <i class="ph-bold ph-image text-lg text-sky-400"></i> Foto Saat Ini
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" name="photo_proof" accept="image/*" class="w-full text-xs font-semibold text-slate-400 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-sky-500/20 file:text-sky-300 hover:file:bg-sky-500/30 border border-white/10 rounded-2xl bg-slate-900/80 cursor-pointer shadow-sm transition-all">
                                </div>

                                <button type="submit" class="w-full bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-sky-500/20 transition-all active:scale-95 flex items-center justify-center gap-2 border border-white/10 mt-6">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Perubahan Jurnal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN: LIST ABSENSI (MANUAL FIX) --}}
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] shadow-2xl flex flex-col h-full overflow-hidden border border-white/10 backdrop-blur-xl text-white">
                        <div class="p-6 md:p-8 border-b border-white/10 bg-gradient-to-r from-sky-900/30 via-blue-900/20 to-slate-900/40 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h3 class="font-black text-white text-xl">Koreksi Absensi Siswa</h3>
                                <p class="text-sm text-slate-300 font-semibold mt-1">Ubah status kehadiran siswa melalui tombol pilihan di sebelah kanan.</p>
                            </div>
                            
                            {{-- Filter Cepat --}}
                            <div class="flex gap-2 shrink-0">
                                <span class="px-4 py-2 rounded-xl bg-rose-950/60 text-rose-300 text-[10px] font-black border border-rose-500/30 uppercase tracking-widest shadow-sm">
                                    Total Alpha: {{ collect($attendances)->where('status', 'alpha')->count() }}
                                </span>
                            </div>
                        </div>

                        <div class="flex-1 p-6 md:p-8 pb-32 md:pb-40 overflow-y-auto max-h-[800px] custom-scrollbar bg-slate-900/40">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($allStudents as $student)
                                    @php
                                        $att = $attendances[$student->id] ?? null;
                                        $status = $att ? $att->status : null; 
                                    @endphp

                                    <div class="flex items-center justify-between p-4 rounded-2xl border border-white/10 bg-slate-900/80 shadow-sm hover:border-sky-500/40 transition-colors group"
                                         id="row-{{ $student->id }}"
                                         x-data="{ 
                                            currentStatus: '{{ $status }}',
                                            open: false,
                                            get statusText() {
                                                const map = { 'present': 'Hadir', 'sick': 'Sakit', 'permission': 'Izin', 'alpha': 'Alpha' };
                                                return this.currentStatus ? map[this.currentStatus] : 'PILIH STATUS';
                                            }
                                         }"
                                         :class="open ? 'z-40 relative' : 'z-0 relative'">
                                        
                                        <div class="flex items-center gap-4 overflow-hidden">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-lg shrink-0 transition-colors shadow-sm"
                                                 :class="{
                                                    'bg-emerald-500 text-white': currentStatus === 'present',
                                                    'bg-rose-500 text-white': currentStatus === 'alpha',
                                                    'bg-sky-500 text-white': currentStatus === 'sick',
                                                    'bg-amber-500 text-white': currentStatus === 'permission',
                                                    'bg-slate-800 text-slate-400 border border-white/10': !currentStatus
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
                                                <p class="font-black text-white text-sm leading-tight truncate">{{ $student->name }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 tracking-wide mt-1 font-mono">{{ $student->student_id }}</p>
                                            </div>
                                        </div>

                                        {{-- Dropdown Action --}}
                                        <div class="relative shrink-0">
                                            <button @click="open = !open" @click.outside="open = false" 
                                                class="px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider border flex items-center gap-2 transition-all shadow-sm active:scale-95"
                                                :class="{
                                                    'bg-slate-800 border-white/10 text-white hover:bg-slate-700': true,
                                                    'bg-rose-950/60 border-rose-500/40 text-rose-300 hover:bg-rose-900/60': currentStatus === 'alpha'
                                                }">
                                                <span x-text="statusText"></span>
                                                <i class="ph-bold ph-caret-down text-sm"></i>
                                            </button>

                                            <div x-show="open" x-transition style="display: none;" class="absolute right-0 mt-2 w-44 bg-[#021124] rounded-2xl shadow-2xl border border-white/10 z-50 p-2 overflow-hidden text-white">
                                                <button @click="updateStatus({{ $student->id }}, 'present'); currentStatus='present'; open=false" class="w-full text-left px-4 py-3 text-xs font-bold text-emerald-300 hover:bg-emerald-950/60 rounded-lg flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-400"></span> Hadir</button>
                                                <button @click="updateStatus({{ $student->id }}, 'sick'); currentStatus='sick'; open=false" class="w-full text-left px-4 py-3 text-xs font-bold text-sky-300 hover:bg-sky-950/60 rounded-lg flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-sky-400"></span> Sakit</button>
                                                <button @click="updateStatus({{ $student->id }}, 'permission'); currentStatus='permission'; open=false" class="w-full text-left px-4 py-3 text-xs font-bold text-amber-300 hover:bg-amber-950/60 rounded-lg flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Izin</button>
                                                <button @click="updateStatus({{ $student->id }}, 'alpha'); currentStatus='alpha'; open=false" class="w-full text-left px-4 py-3 text-xs font-bold text-rose-300 hover:bg-rose-950/60 rounded-lg flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-rose-400"></span> Alpha</button>
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
                                background: '#021124', color: '#fff',
                                customClass: { popup: 'rounded-2xl border border-white/10 shadow-lg font-sans bg-[#021124] text-white' },
                                didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                            })
                            Toast.fire({ icon: 'success', title: 'Data kehadiran berhasil diperbarui!' });
                        }
                    } catch (e) { 
                        Swal.fire({
                            title: 'Terjadi Kesalahan', 
                            text: 'Gagal memperbarui status absensi siswa. Silakan periksa koneksi internet Anda.', 
                            icon: 'error',
                            background: '#021124', color: '#fff',
                            customClass: { popup: 'rounded-[2rem] shadow-2xl font-sans border border-white/10 bg-[#021124] text-white' },
                            confirmButtonColor: '#e11d48',
                            confirmButtonText: 'Tutup'
                        }); 
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>