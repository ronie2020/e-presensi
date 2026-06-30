<x-app-layout>
    <!-- PERHATIKAN: x-data="timeslotManager()" diletakkan di pembungkus paling luar! -->
    <div class="py-6 sm:py-10 font-sans text-elevate-dark relative overflow-hidden" x-data="timeslotManager()">
        
        {{-- Efek Latar Belakang --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
            <div class="relative rounded-[2rem] sm:rounded-[2.5rem] bg-elevate-gradient-main p-6 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/40 rounded-full blur-3xl pointer-events-none group-hover:bg-white/60 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 sm:gap-8">
                    <div class="max-w-xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-soft border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-clock-user"></i> Master Data Waktu
                        </div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Slot Waktu & Jam Ke-
                        </h1>
                        <p class="text-elevate-dark/80 text-xs sm:text-sm font-semibold leading-relaxed">
                            Definisikan urutan jam pelajaran dan jam istirahat sebagai acuan kerangka generator jadwal.
                        </p>
                    </div>

                    <div class="flex flex-row gap-3 w-full md:w-auto">
                        <div class="bg-white/60 backdrop-blur-md px-5 py-4 rounded-2xl border border-white/80 flex-1 md:flex-none text-center md:text-left shadow-sm">
                            <div class="flex items-center justify-center md:justify-start gap-1.5 mb-1 text-elevate-primary">
                                <i class="ph-duotone ph-list-numbers text-lg"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider">Total Jam/Sesi</span>
                            </div>
                            <span class="block text-2xl font-black text-elevate-dark tracking-tight">
                                {{-- PERBAIKAN: Ubah count() menjadi total() --}}
                                {{ $timeslots->total() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Pesan Flash --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm animate-enter">
                    <span class="font-bold text-sm"><i class="ph-bold ph-check-circle mr-2"></i>{{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            @if (session('error') || $errors->any())
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-start gap-3 shadow-sm animate-enter">
                    <i class="ph-bold ph-warning-circle text-xl mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-bold text-sm mb-1">Terjadi Kesalahan:</p>
                        @if(session('error'))
                            <p class="text-xs font-medium">{{ session('error') }}</p>
                        @else
                            <ul class="list-disc list-inside text-xs font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-600 shrink-0"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 lg:gap-8 items-start">
                
                {{-- KIRI: FORM INPUT (md:col-span-5) --}}
                <div class="md:col-span-5 lg:col-span-4 space-y-6" x-data="{ tab: 'manual' }">
                    
                    {{-- Tabs Toggle (Manual vs Upload) --}}
                    <div class="flex bg-slate-100/50 p-1.5 rounded-2xl border border-slate-200">
                        <button @click="tab = 'manual'" :class="tab === 'manual' ? 'bg-white shadow-sm text-elevate-primary font-black border border-slate-200' : 'text-slate-500 font-bold hover:text-elevate-dark'" class="flex-1 py-2.5 text-xs rounded-xl transition-all flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-keyboard"></i> Input Manual
                        </button>
                        <button @click="tab = 'excel'" :class="tab === 'excel' ? 'bg-white shadow-sm text-emerald-600 font-black border border-emerald-100' : 'text-slate-500 font-bold hover:text-emerald-600'" class="flex-1 py-2.5 text-xs rounded-xl transition-all flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-file-xls"></i> Upload Excel
                        </button>
                    </div>

                    {{-- Form Input Manual --}}
                    <div x-show="tab === 'manual'" x-transition class="bg-white p-5 lg:p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>
                        <h3 class="text-lg font-black text-elevate-dark mb-5">Input Slot Baru</h3>
                        
                        <form action="{{ route('timeslots.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Nama / Label Sesi</label>
                                <input type="text" name="name" required placeholder="Contoh: Jam ke-1 / Istirahat" class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Berlaku Pada Hari</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                                    <label class="flex items-center gap-1.5 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                                        <input type="checkbox" name="day_of_week[]" value="{{ $hari }}" checked class="w-4 h-4 text-elevate-primary bg-white border-slate-300 rounded focus:ring-elevate-primary focus:ring-2">
                                        <span class="text-xs font-bold text-elevate-dark">{{ $hari }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Jam Mulai</label>
                                    <input type="time" name="start_time" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5 cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Jam Selesai</label>
                                    <input type="time" name="end_time" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5 cursor-pointer">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Urutan Ke-</label>
                                <input type="number" name="order_sequence" min="1" required placeholder="Misal: 1" class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5">
                            </div>

                            {{-- Switch Istirahat --}}
                            <div x-data="{ isBreak: false }">
                                <label :class="isBreak ? 'bg-amber-50 border-amber-300 shadow-md ring-2 ring-amber-100' : 'bg-slate-50 border-slate-200 hover:bg-slate-100'"
                                       class="mt-4 p-3.5 rounded-xl border flex items-center justify-between cursor-pointer transition-all duration-300 group">
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex items-center shrink-0">
                                            <input type="hidden" name="is_break" value="0">
                                            <input type="checkbox" name="is_break" value="1" class="peer sr-only" x-model="isBreak">
                                            <div class="w-10 h-6 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500 shadow-inner"></div>
                                        </div>
                                        <span class="text-xs font-black select-none transition-colors" :class="isBreak ? 'text-amber-800' : 'text-slate-500 group-hover:text-slate-700'">Tandai Istirahat</span>
                                    </div>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center transition-colors" :class="isBreak ? 'bg-amber-200 text-amber-600' : 'bg-slate-200 text-slate-400'">
                                        <i class="ph-bold ph-coffee text-lg"></i>
                                    </div>
                                </label>
                            </div>
                            
                            <button type="submit" class="w-full py-3 mt-4 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark transition-all shadow-lg flex items-center justify-center gap-2 text-sm">
                                <i class="ph-bold ph-plus-circle"></i> Tambah Slot Waktu
                            </button>
                        </form>
                    </div>

                    {{-- Form Upload Excel --}}
                    <div x-show="tab === 'excel'" x-transition style="display: none;" class="bg-white p-5 lg:p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                        <h3 class="text-lg font-black text-elevate-dark mb-2">Import via Excel</h3>
                        <p class="text-xs font-medium text-slate-500 mb-5">Upload file template CSV/Excel untuk mem-plot puluhan jam sesi sekaligus.</p>

                        <div class="mb-5">
                            <a href="{{ route('timeslots.template') }}" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-4 py-2 rounded-xl transition-colors w-full justify-center shadow-sm">
                                <i class="ph-bold ph-download-simple"></i> Download Template .CSV
                            </a>
                        </div>
                        
                        <form action="{{ route('timeslots.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="{ fileName: '' }" @submit.prevent="if(!fileName) { alert('Silakan pilih file Excel/CSV terlebih dahulu!'); } else { $el.submit(); }">
                            @csrf
                            <div class="relative group cursor-pointer">
                                <input type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                                <div class="w-full rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-8 text-center group-hover:bg-slate-100 group-hover:border-emerald-400 transition-all flex flex-col items-center justify-center gap-2" :class="fileName ? 'border-emerald-400 bg-emerald-50' : ''">
                                    
                                    <i class="ph-duotone ph-upload-simple text-4xl text-slate-400 group-hover:text-emerald-500 transition-colors" x-show="!fileName"></i>
                                    <i class="ph-fill ph-file-excel text-4xl text-emerald-500" x-show="fileName" style="display: none;"></i>
                                    
                                    <span class="text-[10px] font-bold text-slate-500 group-hover:text-elevate-dark transition-colors" x-show="!fileName">Pilih file Excel atau drag ke sini</span>
                                    <span class="text-xs font-black text-emerald-600" x-show="fileName" x-text="fileName" style="display: none;"></span>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-3 mt-4 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2 text-sm active:scale-95">
                                <i class="ph-bold ph-cloud-arrow-up text-lg"></i> Proses Import
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KANAN: TABEL DATA (md:col-span-7) --}}
                <div class="md:col-span-7 lg:col-span-8 bg-white p-5 lg:p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden flex flex-col">
                    
                    {{-- HEADER TABEL & TOMBOL KOSONGKAN (DIPERBARUI) --}}
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                        <h3 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                            <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Daftar Urutan Waktu
                        </h3>
                        
                        {{-- PERBAIKAN: Ubah count() menjadi total() --}}
                        @if($timeslots->total() > 0)
                        <form action="{{ route('timeslots.reset') }}" method="POST" id="form-reset-timeslots">
                            @csrf
                            <button type="button" onclick="confirmResetTimeslots()" class="px-4 py-2 bg-rose-50 hover:bg-rose-500 text-rose-600 hover:text-white border border-rose-200 hover:border-rose-500 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                                <i class="ph-bold ph-trash"></i> Kosongkan Semua
                            </button>
                        </form>
                        @endif
                    </div>
                    
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-bold text-elevate-primary uppercase border-b border-slate-100">
                                <tr>
                                    <th class="px-4 py-3 rounded-tl-xl text-center w-16">Urutan</th>
                                    <th class="px-4 py-3 min-w-[150px]">Nama Sesi</th>
                                    <th class="px-4 py-3 text-center">Hari</th>
                                    <th class="px-4 py-3 text-center">Waktu</th>
                                    <th class="px-4 py-3 text-center">Tipe</th>
                                    <th class="px-4 py-3 text-center rounded-tr-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($timeslots as $slot)
                                <tr class="hover:bg-slate-50/50 transition-colors {{ $slot->is_break ? 'bg-amber-50/30' : '' }}">
                                    <td class="px-4 py-3 text-center">
                                        <div class="w-8 h-8 rounded-lg bg-elevate-soft text-elevate-primary flex items-center justify-center font-black mx-auto border border-elevate-accent/20">
                                            {{ $slot->order_sequence }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-bold text-slate-700 leading-tight">
                                        {{ $slot->name }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $daysArr = array_map('trim', explode(',', $slot->day_of_week));
                                        @endphp
                                        @if($slot->day_of_week === 'Semua Hari' || count($daysArr) >= 5)
                                            <span class="inline-flex items-center px-2 py-1 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold border border-slate-200">Semua Hari</span>
                                        @else
                                            <div class="flex flex-wrap justify-center gap-1 max-w-[120px] mx-auto">
                                                @foreach($daysArr as $d)
                                                    @if($d !== '')
                                                        <span class="inline-flex items-center px-1.5 py-0.5 bg-elevate-soft text-elevate-primary rounded text-[9px] font-bold border border-elevate-accent/20">{{ $d }}</span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 border border-slate-200 text-xs font-mono font-bold text-slate-600">
                                            <i class="ph-bold ph-clock text-slate-400"></i>
                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($slot->is_break)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-wider">
                                                <i class="ph-bold ph-coffee"></i> Istirahat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-black uppercase tracking-wider">
                                                <i class="ph-bold ph-book-open"></i> Pelajaran
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Tombol Edit Baru -->
                                            <button type="button" @click="openEdit({ id: {{ $slot->id }}, name: '{{ addslashes($slot->name) }}', day_of_week: '{{ addslashes($slot->day_of_week) }}', start_time: '{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}', end_time: '{{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}', order_sequence: {{ $slot->order_sequence }}, is_break: {{ $slot->is_break ? 'true' : 'false' }} })" class="w-7 h-7 flex items-center justify-center rounded-lg bg-elevate-soft text-elevate-primary hover:bg-elevate-primary hover:text-white transition-all shadow-sm">
                                                <i class="ph-bold ph-pencil-simple"></i>
                                            </button>
                                            
                                            <form action="{{ route('timeslots.destroy', $slot->id) }}" method="POST" id="delete-form-{{ $slot->id }}" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('{{ $slot->id }}')" class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-16 text-center">
                                        <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="ph-duotone ph-clock text-3xl text-slate-300"></i>
                                        </div>
                                        <p class="text-sm font-bold text-slate-400">Kerangka waktu belum dikonfigurasi.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- TAMBAHKAN BLOK KODE PAGINATION INI --}}
                    @if($timeslots->hasPages())
                    <div class="mt-6 border-t border-slate-100 pt-5">
                        {{ $timeslots->links() }}
                    </div>
                    @endif
                    
                </div>

            </div>
        </div>

        {{-- ===================================================================== --}}
        {{-- MODAL EDIT SLOT WAKTU (ALPINE.JS) - TELEPORT KE BODY --}}
        {{-- ===================================================================== --}}
        <template x-teleport="body">
            <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-sm" x-transition.opacity>
                
                {{-- max-h-[90vh] memastikan modal tidak akan pernah lebih tinggi dari layar --}}
                <div @click.outside="showEditModal = false" class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg border border-slate-100 flex flex-col max-h-[90vh] relative overflow-hidden" x-show="showEditModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    
                    {{-- HEADER MODAL --}}
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between shrink-0">
                        <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2">
                            <i class="ph-duotone ph-pencil-simple text-elevate-primary text-2xl"></i> Edit Slot Waktu
                        </h3>
                        <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-rose-500 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-rose-50"><i class="ph-bold ph-x text-lg"></i></button>
                    </div>

                    <form :action="'{{ url('timeslots') }}/' + editData.id" method="POST" class="flex flex-col flex-1 overflow-hidden">
                        @csrf
                        @method('PUT')
                        
                        {{-- BODY KONTEN (Bisa di-scroll) --}}
                        <div class="p-6 space-y-5 overflow-y-auto custom-scrollbar flex-1">
                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Nama / Label Sesi</label>
                                <input type="text" name="name" x-model="editData.name" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-elevate-accent outline-none py-3">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Berlaku Pada Hari</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                                    <label class="flex items-center gap-1.5 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                                        <input type="checkbox" name="day_of_week[]" value="{{ $hari }}" x-model="editData.days" class="w-4 h-4 text-elevate-primary bg-white border-slate-300 rounded focus:ring-elevate-primary focus:ring-2">
                                        <span class="text-xs font-bold text-elevate-dark">{{ $hari }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Jam Mulai</label>
                                    <input type="time" name="start_time" x-model="editData.start_time" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-elevate-accent outline-none py-3 cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Jam Selesai</label>
                                    <input type="time" name="end_time" x-model="editData.end_time" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-elevate-accent outline-none py-3 cursor-pointer">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Urutan Ke-</label>
                                <input type="number" name="order_sequence" x-model="editData.order_sequence" min="1" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-elevate-accent outline-none py-3">
                            </div>

                            <div>
                                <label :class="editData.is_break ? 'bg-amber-50 border-amber-300 shadow-md ring-2 ring-amber-100' : 'bg-slate-50 border-slate-200 hover:bg-slate-100'"
                                       class="p-4 rounded-xl border flex items-center justify-between cursor-pointer transition-all duration-300">
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex items-center shrink-0">
                                            <input type="hidden" name="is_break" value="0">
                                            <input type="checkbox" name="is_break" value="1" class="peer sr-only" x-model="editData.is_break">
                                            <div class="w-10 h-6 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500 shadow-inner"></div>
                                        </div>
                                        <span class="text-xs font-black select-none transition-colors" :class="editData.is_break ? 'text-amber-800' : 'text-slate-500'">Tandai Sebagai Istirahat</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- FOOTER MODAL (Tombol) --}}
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/80 flex items-center justify-end gap-3 shrink-0">
                            <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-slate-500 font-bold hover:bg-slate-200 rounded-xl text-sm transition-colors">Batal</button>
                            <button type="submit" class="px-7 py-2.5 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark shadow-lg shadow-elevate-primary/30 transition-all active:scale-95 flex items-center gap-2 text-sm">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
        {{-- Akhir dari Modal --}}
    </div>

    {{-- SweetAlert2 Library & Custom Script diletakkan di LUAR Div Utama Alpine --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Alpine Component Logic
        document.addEventListener('alpine:init', () => {
            Alpine.data('timeslotManager', () => ({
                showEditModal: false,
                editData: {
                    id: '',
                    name: '',
                    days: [],
                    start_time: '',
                    end_time: '',
                    order_sequence: '',
                    is_break: false
                },
                openEdit(data) {
                    this.editData = {
                        id: data.id,
                        name: data.name,
                        days: data.day_of_week === 'Semua Hari' ? ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] : data.day_of_week.split(',').map(d => d.trim()),
                        start_time: data.start_time,
                        end_time: data.end_time,
                        order_sequence: data.order_sequence,
                        is_break: data.is_break
                    };
                    this.showEditModal = true;
                }
            }));
        });
           
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Slot Waktu?',
                text: "Anda yakin ingin menghapus slot waktu ini? Jika jadwal sudah digenerate, ini bisa mempengaruhi tampilan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl w-[90%] max-w-md',
                    confirmButton: 'bg-rose-600 text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-1 sm:mx-2 shadow-lg shadow-rose-900/20 text-sm',
                    cancelButton: 'bg-slate-100 text-slate-600 px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-1 sm:mx-2 text-sm'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form-' + id);
                    if (form) form.submit();
                }
            });
        }

        function confirmResetTimeslots() {
            Swal.fire({
                title: 'Kosongkan Semua Slot?',
                text: "PERINGATAN: Ini akan menghapus SELURUH slot waktu. Jika jadwal kelas sudah pernah di-generate, jadwal tersebut juga akan ikut dihapus. Lanjutkan?",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus Semuanya!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl w-[90%] max-w-md',
                    confirmButton: 'bg-rose-600 text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-1 sm:mx-2 shadow-lg shadow-rose-900/20 text-sm',
                    cancelButton: 'bg-slate-100 text-slate-600 px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-1 sm:mx-2 text-sm'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-reset-timeslots');
                    if (form) form.submit();
                }
            });
        }
    </script>
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.5s ease-out forwards; }
    </style>
</x-app-layout>