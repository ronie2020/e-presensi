{{-- Halaman ini adalah tampilan untuk resources/views/classes/edit.blade.php --}}
<x-app-layout>
    <div class="py-12 font-sans text-elevate-dark relative overflow-hidden min-h-screen">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Header with Back Button --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-elevate-dark tracking-tight leading-none">Edit Kelas</h1>
                    <p class="text-sm font-semibold text-elevate-dark/60 mt-2">Perbarui informasi rombongan belajar.</p>
                </div>
                <a href="{{ route('classes.index') }}" class="group flex items-center gap-2 bg-white/60 backdrop-blur-md px-4 py-2.5 rounded-2xl shadow-sm border border-white hover:border-elevate-accent/50 hover:shadow-md transition-all">
                    <div class="w-7 h-7 rounded-full bg-elevate-soft flex items-center justify-center text-slate-400 group-hover:bg-elevate-primary group-hover:text-white transition-colors">
                        <i class="ph-bold ph-arrow-left text-sm"></i>
                    </div>
                    <span class="text-xs font-bold text-elevate-dark/70 group-hover:text-elevate-primary">Kembali</span>
                </a>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                {{-- Aksen Gradient --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>
                
                <div class="p-8 sm:p-10">
                    {{-- Info Card --}}
                    <div class="flex items-start gap-4 mb-8 p-6 bg-elevate-gradient-card rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 text-elevate-primary/5 text-7xl pointer-events-none">
                            <i class="ph-fill ph-chalkboard-teacher"></i>
                        </div>
                        <div class="w-14 h-14 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center shadow-sm border border-elevate-accent/20 text-3xl shrink-0 relative z-10">
                            <i class="ph-duotone ph-pencil-simple-line"></i>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-lg font-black text-elevate-dark">Formulir Perubahan</h3>
                            <p class="text-xs text-elevate-dark/70 font-medium leading-relaxed mt-1">Ubah nama atau wali kelas untuk rombel <strong class="text-elevate-primary bg-elevate-soft px-2 py-0.5 rounded border border-elevate-accent/20 shadow-sm inline-block mt-0.5">{{ $class->name }}</strong>.</p>
                        </div>
                    </div>

                    {{-- Tampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="mb-8 p-5 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm flex items-start gap-3 shadow-sm">
                            <div class="p-1.5 bg-rose-100 rounded-xl shrink-0 mt-0.5 border border-rose-200">
                                <i class="ph-bold ph-warning-circle text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold mb-1">Terjadi kesalahan input:</p>
                                <ul class="list-disc list-inside opacity-80 text-xs font-medium">
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
                            <label for="name" class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Nama Kelas</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary transition-colors">
                                    <i class="ph-bold ph-chalkboard text-lg"></i>
                                </div>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $class->name) }}" required 
                                       class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm placeholder:font-medium placeholder:text-slate-400 outline-none">
                            </div>
                        </div>
                        
                        {{-- Dropdown Wali Kelas --}}
                        <div>
                            <label for="homeroom_teacher_id" class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Wali Kelas (Opsional)</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary transition-colors">
                                    <i class="ph-bold ph-user-circle text-lg"></i>
                                </div>
                                <select name="homeroom_teacher_id" id="homeroom_teacher_id" 
                                        class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer outline-none">
                                    <option value="">-- Pilih Wali Kelas --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" 
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

                        <div class="pt-6 border-t border-slate-100 mt-8 flex gap-3">
                            <a href="{{ route('classes.index') }}" class="px-6 py-4 bg-slate-100 text-elevate-dark/60 font-bold rounded-2xl hover:bg-slate-200 transition-colors text-center text-sm border border-transparent flex-1 md:flex-none">
                                Batal
                            </a>
                            <button type="submit" class="flex-1 py-4 px-6 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
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