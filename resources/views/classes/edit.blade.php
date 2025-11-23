{{-- Halaman ini adalah tampilan untuk resources/views/classes/edit.blade.php --}}
<x-app-layout>
    <div class="py-12">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header with Back Button --}}
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Edit Kelas</h1>
                <a href="{{ route('classes.index') }}" class="text-sm font-bold text-gray-500 hover:text-indigo-600 flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-indigo-100/50 border border-indigo-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Update Informasi Kelas</h3>
                    </div>

                    {{-- Tampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-sm">
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
                            <label for="name" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Kelas</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $class->name) }}" required 
                                   class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 font-bold text-gray-700 transition-colors">
                        </div>
                        
                        {{-- Dropdown Wali Kelas --}}
                        <div>
                            <label for="homeroom_teacher_id" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Wali Kelas</label>
                            <div class="relative">
                                <select name="homeroom_teacher_id" id="homeroom_teacher_id" 
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 font-medium transition-colors appearance-none">
                                    <option value="">-- Pilih Wali Kelas --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" 
                                            {{ old('homeroom_teacher_id', $class->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full py-3 px-6 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>