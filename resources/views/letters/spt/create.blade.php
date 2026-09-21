<x-app-layout>
    {{-- Load SweetAlert & Alpine.js --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen" 
         x-data="{ 
            incomingLetters: {{ $incoming_letters->toJson() }},
            selectedLetterId: '{{ $selected_letter_id ?? '' }}',
            perihal: '{{ old('untuk') }}',
            tempat: '{{ old('tempat') }}',
            followers: {{ old('pengikut') ? Js::from(old('pengikut')) : '[]' }},
            users: {{ Js::from($users->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'nip' => $u->nip])) }},

            init() {
                if(this.selectedLetterId) {
                    this.updateFromLetter();
                }
            },

            updateFromLetter() {
                const letter = this.incomingLetters.find(l => l.id == this.selectedLetterId);
                if(letter) {
                    this.perihal = letter.perihal;
                    this.tempat = letter.asal_surat;
                }
            },

            addFollower() {
                this.followers.push({ nama: '', nip: '', keterangan: '' });
            },

            removeFollower(index) {
                this.followers.splice(index, 1);
            },

            fillFollowerData(index, event) {
                const selectedId = event.target.value;
                const user = this.users.find(u => u.id == selectedId);
                if (user) {
                    this.followers[index].nama = user.name;
                    this.followers[index].nip = user.nip || '-';
                }
            }
         }">
        
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('letters.spt.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-[#56bbf1] mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            {{-- Card Container --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative">
                
                {{-- Header --}}
                <div class="bg-gradient-to-r from-[#031d3d] via-[#0b2545] to-[#134074] p-8 text-white relative overflow-hidden border-b border-white/10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-[#56bbf1]/10 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-paper-plane-tilt"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-paper-plane-tilt text-[#56bbf1]"></i> Buat Surat Perintah Tugas
                    </h2>
                    <p class="text-slate-300 text-sm font-medium relative z-10 mt-1">Lengkapi rincian penugasan dinas pegawai.</p>
                </div>

                {{-- Form Content --}}
                <div class="p-6 sm:p-8">
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-300 rounded-2xl text-sm flex items-start gap-3 shadow-sm">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5 text-rose-400"></i>
                            <div>
                                <strong class="font-bold block mb-1 text-rose-300">Periksa kembali inputan Anda!</strong>
                                <ul class="list-disc list-inside font-medium">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form id="form-create-spt" action="{{ route('letters.spt.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- SECTION 1: DASAR & IDENTITAS -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">1</span>
                                Identitas & Dasar SPT
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Nomor SPT (Otomatis)</label>
                                    <input type="text" name="nomor_spt" value="{{ old('nomor_spt', $nomor_otomatis) }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 font-mono font-bold text-[#56bbf1] text-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Berdasarkan Surat Masuk (Opsional)</label>
                                    <div class="relative">
                                        <select name="letter_incoming_id" x-model="selectedLetterId" @change="updateFromLetter()" class="w-full pl-4 pr-10 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white appearance-none transition-all cursor-pointer">
                                            <option value="" class="bg-slate-900 text-white">-- Pilih Dasar Surat --</option>
                                            @foreach($incoming_letters as $letter)
                                                <option value="{{ $letter->id }}" class="bg-slate-900 text-white">{{ $letter->nomor_surat }} - {{ $letter->perihal }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: PEGAWAI UTAMA -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">2</span>
                                Pegawai yang Ditugaskan <span class="text-rose-400">*</span>
                            </h3>
                            <div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-4 bg-slate-900/80 rounded-3xl border border-white/10 custom-scrollbar shadow-inner">
                                    @foreach($users as $user)
                                        <label class="flex items-center gap-3 p-3 rounded-xl border border-white/5 hover:border-[#56bbf1]/30 hover:bg-[#56bbf1]/10 transition-all cursor-pointer group/item">
                                            <input type="checkbox" name="pegawai_ids[]" value="{{ $user->id }}" 
                                                class="rounded-md border-white/20 bg-slate-800 text-[#56bbf1] focus:ring-[#56bbf1] w-5 h-5 transition-all">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-white group-hover/item:text-[#56bbf1]">{{ $user->name }}</span>
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">NIP. {{ $user->nip ?? '-' }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-[10px] text-slate-400 mt-3 ml-1 italic font-medium">
                                    <i class="ph-fill ph-info text-[#56bbf1]"></i> Pilih satu atau lebih personil inti penugasan.
                                </p>
                            </div>
                        </div>

                        <!-- SECTION 3: DETAIL TUGAS -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">3</span>
                                Lokasi & Maksud Kegiatan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Maksud Perjalanan / Perihal <span class="text-rose-400">*</span></label>
                                    <textarea name="untuk" x-model="perihal" rows="2" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-medium text-white transition-all placeholder:text-slate-500" placeholder="Contoh: Menghadiri undangan rapat koordinasi..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tempat Tujuan <span class="text-rose-400">*</span></label>
                                    <input type="text" name="tempat" x-model="tempat" required placeholder="Contoh: Dinas Pendidikan Propinsi" class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all placeholder:text-slate-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tgl Berangkat <span class="text-rose-400">*</span></label>
                                    <input type="date" name="tgl_berangkat" value="{{ old('tgl_berangkat') }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all [color-scheme:dark]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tgl Kembali <span class="text-rose-400">*</span></label>
                                    <input type="date" name="tgl_kembali" value="{{ old('tgl_kembali') }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all [color-scheme:dark]">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: PENGIKUT -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider flex items-center gap-2">
                                    <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">4</span>
                                    Peserta Tambahan (Pengikut)
                                </h3>
                                <button type="button" @click="addFollower()" class="px-4 py-2 bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-xl text-xs font-bold hover:bg-[#56bbf1]/30 transition-colors flex items-center gap-2 shadow-sm">
                                    <i class="ph-bold ph-plus"></i> Tambah Pengikut
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(follower, index) in followers" :key="index">
                                    <div class="flex flex-col md:flex-row gap-4 items-end bg-slate-900/80 p-5 rounded-2xl border border-white/10 relative group/follower transition-all hover:border-[#56bbf1]/40 shadow-sm">
                                        <div class="flex-1 w-full">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pilih Pegawai (Cepat)</label>
                                            <select class="block w-full text-xs border border-white/10 rounded-xl mb-2 focus:border-[#56bbf1] bg-slate-800 text-white transition-all" @change="fillFollowerData(index, $event)">
                                                <option value="" class="bg-slate-900 text-white">-- Pilih untuk Auto-fill --</option>
                                                <template x-for="u in users" :key="u.id">
                                                    <option :value="u.id" x-text="u.name" class="bg-slate-900 text-white"></option>
                                                </template>
                                            </select>
                                            <input type="text" :name="'pengikut['+index+'][nama]'" x-model="follower.nama" placeholder="Nama Lengkap" class="block w-full text-sm font-bold border border-white/10 rounded-xl focus:border-[#56bbf1] bg-slate-900/60 text-white" required>
                                        </div>
                                        <div class="w-full md:w-1/4">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">NIP / NIK</label>
                                            <input type="text" :name="'pengikut['+index+'][nip]'" x-model="follower.nip" class="block w-full text-sm font-mono border border-white/10 rounded-xl focus:border-[#56bbf1] bg-slate-900/60 text-slate-300" placeholder="-">
                                        </div>
                                        <div class="w-full md:w-1/3">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Keterangan</label>
                                            <input type="text" :name="'pengikut['+index+'][keterangan]'" x-model="follower.keterangan" placeholder="Contoh: Anggota" class="block w-full text-sm border border-white/10 rounded-xl focus:border-[#56bbf1] bg-slate-900/60 text-slate-300">
                                        </div>
                                        <div class="absolute top-2 right-2 md:static">
                                            <button type="button" @click="removeFollower(index)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-800 border border-white/10 text-slate-400 hover:text-rose-400 hover:border-rose-500/30 hover:bg-rose-500/10 transition-all shadow-sm">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <div x-show="followers.length === 0" class="text-center py-10 border-2 border-dashed border-white/10 rounded-2xl text-slate-500 italic text-sm bg-slate-900/40">
                                    <i class="ph-bold ph-users-three text-2xl block mb-2 opacity-50 text-slate-400"></i>
                                    Tidak ada peserta tambahan.
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-8 border-t border-white/10 flex items-center justify-end gap-4">
                            <a href="{{ route('letters.spt.index') }}" class="px-6 py-3.5 rounded-2xl text-slate-400 font-bold text-sm bg-slate-800/80 hover:bg-slate-700 hover:text-white transition-colors border border-white/10">
                                Batalkan
                            </a>
                            <button type="button" onclick="confirmSubmit(event)" class="px-10 py-3.5 bg-gradient-to-r from-[#56bbf1] to-blue-600 hover:brightness-110 text-white font-bold rounded-2xl shadow-lg shadow-[#56bbf1]/20 transition-all transform active:scale-95 flex items-center gap-2 group">
                                <i class="ph-bold ph-check-circle text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Terbitkan SPT</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmSubmit(e) {
            e.preventDefault();
            const form = document.getElementById('form-create-spt');
            
            // Validasi Checkbox Pegawai Utama
            const checkedCount = document.querySelectorAll('input[name="pegawai_ids[]"]:checked').length;
            if (checkedCount === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pegawai Kosong',
                    text: 'Silakan pilih minimal satu pegawai yang ditugaskan.',
                    background: '#021124', color: '#fff',
                    customClass: { popup: 'rounded-[2rem] border border-white/10' }
                });
                return;
            }

            if (!form.checkValidity()) { 
                form.reportValidity(); 
                return; 
            }

            Swal.fire({
                title: 'Simpan & Terbitkan?', 
                text: 'SPT akan diterbitkan dan draft SPPD otomatis akan dibuat.', 
                icon: 'question',
                showCancelButton: true, 
                confirmButtonText: 'Ya, Terbitkan!', 
                cancelButtonText: 'Cek Lagi',
                reverseButtons: true, 
                background: '#021124', color: '#fff',
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl',
                    confirmButton: 'bg-gradient-to-r from-[#56bbf1] to-blue-600 text-white px-8 py-3.5 rounded-2xl font-bold hover:brightness-110 mx-2 shadow-lg shadow-[#56bbf1]/20',
                    cancelButton: 'bg-slate-800 text-slate-300 px-8 py-3.5 rounded-2xl font-bold hover:bg-slate-700 mx-2 border border-white/10'
                }, 
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ 
                        title: 'Memproses Data...', 
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false, 
                        background: '#021124', color: '#fff',
                        didOpen: () => Swal.showLoading() 
                    });
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>