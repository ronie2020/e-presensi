<x-app-layout>
    <div class="py-12 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Header with Back Button --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight leading-none">Edit Kelas</h1>
                    <p class="text-sm font-semibold text-slate-400 mt-2">Perbarui informasi rombongan belajar.</p>
                </div>
                <a href="{{ route('classes.index') }}" class="group flex items-center gap-2 bg-white/5 backdrop-blur-md px-4 py-2.5 rounded-2xl shadow-sm border border-white/10 hover:border-sky-400/40 hover:bg-sky-500/20 transition-all">
                    <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-slate-300 group-hover:bg-sky-500 group-hover:text-white transition-colors">
                        <i class="ph-bold ph-arrow-left text-sm"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-300 group-hover:text-white">Kembali</span>
                </a>
            </div>

            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 backdrop-blur-xl overflow-hidden relative">
                {{-- Aksen Gradient --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] to-[#56bbf1]"></div>
                
                <div class="p-8 sm:p-10">
                    {{-- Info Card --}}
                    <div class="flex items-start gap-4 mb-8 p-6 bg-white/5 rounded-[2rem] border border-white/10 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 text-white/5 text-7xl pointer-events-none">
                            <i class="ph-fill ph-chalkboard-teacher"></i>
                        </div>
                        <div class="w-14 h-14 bg-sky-500/20 text-sky-400 rounded-2xl flex items-center justify-center shadow-sm border border-sky-400/30 text-3xl shrink-0 relative z-10">
                            <i class="ph-duotone ph-pencil-simple-line"></i>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-lg font-black text-white">Formulir Perubahan</h3>
                            <p class="text-xs text-slate-300 font-medium leading-relaxed mt-1">Ubah nama atau wali kelas untuk rombel <strong class="text-sky-300 bg-sky-500/10 px-2 py-0.5 rounded border border-sky-400/20 shadow-sm inline-block mt-0.5">{{ $class->name }}</strong>.</p>
                        </div>
                    </div>

                    {{-- Tampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="mb-8 p-5 bg-rose-500/10 border border-rose-500/30 text-rose-300 rounded-2xl text-sm flex items-start gap-3 shadow-xl backdrop-blur-xl">
                            <div class="p-1.5 bg-rose-500/20 rounded-xl shrink-0 mt-0.5 border border-rose-500/30 text-rose-400">
                                <i class="ph-bold ph-warning-circle text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold mb-1 text-rose-200">Terjadi kesalahan input:</p>
                                <ul class="list-disc list-inside text-rose-300 text-xs font-medium">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('classes.update', $class->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        {{-- Nama Kelas --}}
                        <div>
                            <label for="name" class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Nama Kelas</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors">
                                    <i class="ph-bold ph-chalkboard text-lg"></i>
                                </div>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $class->name) }}" required 
                                       class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold text-white transition-all shadow-sm placeholder:font-medium placeholder:text-slate-500 [color-scheme:dark] outline-none">
                            </div>
                        </div>
                        
                        {{-- Dropdown Wali Kelas --}}
                        <div>
                            <label for="homeroom_teacher_id" class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Wali Kelas (Opsional)</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors">
                                    <i class="ph-bold ph-user-circle text-lg"></i>
                                </div>
                                <select name="homeroom_teacher_id" id="homeroom_teacher_id" 
                                        class="w-full pl-11 pr-10 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold text-white transition-all shadow-sm appearance-none cursor-pointer [color-scheme:dark] outline-none">
                                    <option value="" class="bg-slate-900 text-white">-- Pilih Wali Kelas --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" class="bg-slate-900 text-white" 
                                            {{ old('homeroom_teacher_id', $class->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-white/10 mt-8 flex gap-3">
                            <a href="{{ route('classes.index') }}" class="px-6 py-4 bg-white/5 border border-white/10 text-slate-300 font-bold rounded-2xl hover:bg-white/10 hover:text-white transition-colors text-center text-sm flex-1 md:flex-none">
                                Batal
                            </a>
                            <button type="submit" class="flex-1 py-4 px-6 bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] hover:from-sky-600 hover:to-sky-400 text-white font-bold rounded-2xl transition-all shadow-lg shadow-sky-600/25 flex items-center justify-center gap-2 transform active:scale-95 border border-white/20">
                                <i class="ph-bold ph-floppy-disk text-xl"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>