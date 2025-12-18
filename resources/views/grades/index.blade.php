<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-violet-100 text-violet-600 mb-4 shadow-sm animate-bounce-slow">
                    <i class="ph-duotone ph-exam text-4xl"></i>
                </div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Akademik & E-Rapor</h1>
                <p class="text-slate-500 mt-2 text-lg">Kelola nilai dan cetak hasil belajar siswa.</p>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-emerald-100 p-1 rounded-lg"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- Grid dengan 'items-stretch' agar tinggi kartu sama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
                
                {{-- CARD 1: INPUT NILAI --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-violet-500/5 border border-slate-100 overflow-hidden relative group hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-violet-500 to-fuchsia-500"></div>
                    <div class="p-8 flex flex-col h-full">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-2xl">
                                <i class="ph-duotone ph-pencil-simple-line"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800">Input Nilai</h2>
                                <p class="text-sm text-slate-400">Isi nilai per mata pelajaran.</p>
                            </div>
                        </div>

                        <form action="{{ route('grades.create') }}" method="GET" class="flex-1 flex flex-col">
                            <div class="space-y-4 flex-1">
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 focus-within:ring-2 ring-violet-200 transition">
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Kelas</label>
                                    <select name="class_id" class="w-full rounded-lg border-slate-200 text-sm font-bold text-slate-700 focus:ring-violet-500 cursor-pointer" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 focus-within:ring-2 ring-violet-200 transition">
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Mata Pelajaran</label>
                                    <select name="subject_id" class="w-full rounded-lg border-slate-200 text-sm font-bold text-slate-700 focus:ring-violet-500 cursor-pointer" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach($subjects as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Tahun</label>
                                        <select name="academic_year" class="w-full rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                                            @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Semester</label>
                                        <select name="semester" class="w-full rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                                            <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil (1)</option>
                                            <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap (2)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full mt-6 py-3 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 transition flex items-center justify-center gap-2 shadow-lg shadow-violet-500/20">
                                <span>Mulai Input</span> <i class="ph-bold ph-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CARD 2: CETAK RAPOR --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-500/5 border border-slate-100 overflow-hidden relative group hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-cyan-500"></div>
                    <div class="p-8 flex flex-col h-full">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                                <i class="ph-duotone ph-printer"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800">Cetak E-Rapor</h2>
                                <p class="text-sm text-slate-400">Lihat hasil & cetak dokumen.</p>
                            </div>
                        </div>

                        <form action="{{ route('grades.list') }}" method="GET" class="flex-1 flex flex-col">
                            <div class="space-y-4 flex-1">
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 focus-within:ring-2 ring-blue-200 transition">
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Kelas</label>
                                    <select name="class_id" class="w-full rounded-lg border-slate-200 text-sm font-bold text-slate-700 focus:ring-blue-500 cursor-pointer" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                    </select>
                                </div>
                                
                                {{-- PERBAIKAN: Spacer diganti dengan margin otomatis (mt-auto) di flex container --}}
                                <div class="bg-blue-50/50 rounded-xl border border-blue-100 p-4 flex items-center gap-3">
                                    <i class="ph-fill ph-info text-blue-400"></i>
                                    <span class="text-xs text-blue-600 font-medium leading-tight">Pilih kelas di atas untuk melihat daftar siswa yang siap dicetak rapornya.</span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mt-auto">
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Tahun</label>
                                        <select name="academic_year" class="w-full rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                                            @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Semester</label>
                                        <select name="semester" class="w-full rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                                            <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil (1)</option>
                                            <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap (2)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
                                <i class="ph-bold ph-list-magnifying-glass"></i> <span>Lihat Siswa</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>