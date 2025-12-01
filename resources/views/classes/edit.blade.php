{{-- Halaman ini adalah tampilan untuk resources/views/classes/edit.blade.php --}}
<x-app-layout>
    <div class="py-12">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header with Back Button --}}
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight leading-none">Edit Kelas</h1>
                    <p class="text-sm text-slate-500 mt-1">Perbarui informasi rombongan belajar.</p>
                </div>
                <a href="{{ route('classes.index') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 flex items-center gap-1.5 transition-colors bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-200">
                    <i class="ph-bold ph-arrow-left"></i>
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-blue-500/5 border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-8 p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                        <div class="w-12 h-12 bg-white text-blue-600 rounded-xl flex items-center justify-center shadow-sm border border-blue-50 text-2xl">
                            <i class="ph-duotone ph-pencil-simple-line"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Formulir Perubahan</h3>
                            <p class="text-xs text-slate-500">Ubah nama atau wali kelas di bawah ini.</p>
                        </div>
                    </div>

                    {{-- Tampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-sm flex items-start gap-3">
                            <i class="ph-fill ph-warning-circle text-lg mt-0.5"></i>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('classes.update', $class->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        {{-- Nama Kelas --}}
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Kelas</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="ph-bold ph-chalkboard"></i>
                                </div>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $class->name) }}" required 
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 transition-colors placeholder:font-normal">
                            </div>
                        </div>
                        
                        {{-- Dropdown Wali Kelas --}}
                        <div>
                            <label for="homeroom_teacher_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Wali Kelas</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                    <i class="ph-bold ph-user-circle"></i>
                                </div>
                                <select name="homeroom_teacher_id" id="homeroom_teacher_id" 
                                        class="w-full pl-10 pr-10 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-medium text-slate-700 transition-colors appearance-none cursor-pointer">
                                    <option value="">-- Pilih Wali Kelas --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" 
                                            {{ old('homeroom_teacher_id', $class->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-50 mt-6">
                            <button type="submit" 
                                    class="w-full py-3.5 px-6 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-floppy-disk"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>