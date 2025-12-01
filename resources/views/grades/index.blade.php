<x-app-layout>
    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-violet-100 text-violet-600 mb-4 shadow-sm">
                    <i class="ph-duotone ph-exam text-4xl"></i>
                </div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Input Nilai & E-Rapor</h1>
                <p class="text-slate-500 mt-2 text-lg max-w-lg mx-auto">
                    Pilih kelas dan mata pelajaran untuk mulai mengisi penilaian siswa.
                </p>
            </div>

            {{-- Flash Message --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in-down">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-emerald-100 p-1 rounded-lg transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-xl shadow-violet-500/5 border border-slate-100 overflow-hidden relative">
                {{-- Dekorasi Atas --}}
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-violet-500 to-fuchsia-500"></div>

                <div class="p-8 md:p-10">
                    <form action="{{ route('grades.create') }}" method="GET">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            
                            <!-- Pilih Kelas -->
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                    <i class="ph-bold ph-chalkboard-teacher"></i> Kelas
                                </label>
                                <div class="relative group">
                                    <select name="class_id" class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 font-bold text-slate-700 transition-all cursor-pointer appearance-none" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-violet-500 transition-colors">
                                        <i class="ph-bold ph-users-three text-lg"></i>
                                    </div>
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Pilih Mata Pelajaran -->
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                    <i class="ph-bold ph-book-bookmark"></i> Mata Pelajaran
                                </label>
                                <div class="relative group">
                                    <select name="subject_id" class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 font-bold text-slate-700 transition-all cursor-pointer appearance-none" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach($subjects as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-violet-500 transition-colors">
                                        <i class="ph-bold ph-bookmarks text-lg"></i>
                                    </div>
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Tahun Ajaran -->
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                    <i class="ph-bold ph-calendar"></i> Tahun Ajaran
                                </label>
                                <div class="relative">
                                    <select name="academic_year" class="w-full pl-4 pr-10 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 font-medium text-slate-700 transition-all appearance-none cursor-pointer">
                                        @foreach($years as $y)
                                            <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>
                                                {{ $y->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Semester -->
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                    <i class="ph-bold ph-clock-counter-clockwise"></i> Semester
                                </label>
                                <div class="relative">
                                    <select name="semester" class="w-full pl-4 pr-10 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 font-medium text-slate-700 transition-all appearance-none cursor-pointer">
                                        <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil (1)</option>
                                        <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap (2)</option>
                                    </select>
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white font-bold rounded-xl hover:from-violet-700 hover:to-fuchsia-700 transition-all shadow-lg shadow-violet-500/30 flex items-center justify-center gap-2 transform active:scale-[0.98] text-lg">
                            <span>Mulai Input Nilai</span>
                            <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>