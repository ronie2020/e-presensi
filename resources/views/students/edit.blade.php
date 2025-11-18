{{-- Halaman ini adalah tampilan untuk resources/views/students/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-medium mb-4">Edit Siswa: {{ $student->name }}</h3>

                    {{-- Tampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form ini akan dikirim ke route 'students.update' --}}
                    <form action="{{ route('students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PATCH') {{-- Method 'PATCH' (atau 'PUT') wajib untuk update --}}
                        
                        {{-- ID Siswa (Unik, misal: NISN) --}}
                        <div class="mb-4">
                            <label for="student_id" class="block text-sm font-medium text-gray-700">ID Siswa (Unik, misal: NISN)</label>
                            <input type="text" name="student_id" id="student_id" 
                                   value="{{ old('student_id', $student->student_id) }}" {{-- Tampilkan data lama --}}
                                   required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        {{-- Nama Lengkap Siswa --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap Siswa</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $student->name) }}" {{-- Tampilkan data lama --}}
                                   required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        {{-- ID RFID (Opsional) --}}
                        <div class="mb-4">
                            <label for="rfid_id" class="block text-sm font-medium text-gray-700">ID RFID (Opsional)</label>
                            <input type="text" name="rfid_id" id="rfid_id" 
                                   value="{{ old('rfid_id', $student->rfid_id) }}" {{-- Tampilkan data lama --}}
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        {{-- Kelas --}}
                        <div class="mb-4">
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select name="class_id" id="class_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" 
                                        {{-- Pilih kelas yang sudah tersimpan --}}
                                        {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Nomor WA Orang Tua --}}
                        <div class="mb-4">
                            <label for="parent_wa_number" class="block text-sm font-medium text-gray-700">Nomor WA Orang Tua (Format: 62...)</label>
                            <input type="text" name="parent_wa_number" id="parent_wa_number" 
                                   value="{{ old('parent_wa_number', $student->parent_wa_number) }}" {{-- Tampilkan data lama --}}
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('students.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>