{{-- Halaman ini adalah tampilan untuk resources/views/students/edit.blade.php --}}
<x-app-layout>
    <div class="py-12">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Edit Data Siswa</h1>
                <a href="{{ route('students.index') }}" class="text-sm font-bold text-gray-500 hover:text-violet-600 flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-xl shadow-violet-100/50 border border-violet-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-violet-500 to-fuchsia-600"></div>
                
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Update Informasi</h3>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-sm font-medium">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('students.update', $student->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">ID Siswa (NISN)</label>
                            <input type="text" name="student_id" value="{{ old('student_id', $student->student_id) }}" required 
                                   class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-3 font-bold text-gray-700 transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $student->name) }}" required 
                                   class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-3 font-bold text-gray-700 transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kelas</label>
                            <div class="relative">
                                <select name="class_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-3 font-medium transition-colors appearance-none">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">ID RFID</label>
                                <input type="text" name="rfid_id" value="{{ old('rfid_id', $student->rfid_id) }}" 
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-3 font-mono transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">WA Orang Tua</label>
                                <input type="text" name="parent_wa_number" value="{{ old('parent_wa_number', $student->parent_wa_number) }}" 
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-3 font-mono transition-colors">
                            </div>
                        </div>

                        <div class="pt-4 flex items-center gap-3">
                            <a href="{{ route('students.index') }}" class="w-1/3 py-3 text-center bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="w-2/3 py-3 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 transition-all shadow-lg shadow-violet-200">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>