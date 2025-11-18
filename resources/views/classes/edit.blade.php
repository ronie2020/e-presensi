{{-- Halaman ini adalah tampilan untuk resources/views/classes/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-medium mb-4">Edit Kelas: {{ $class->name }}</h3>

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

                    {{-- Form ini akan dikirim ke route 'classes.update' --}}
                    <form action="{{ route('classes.update', $class->id) }}" method="POST">
                        @csrf
                        @method('PATCH') {{-- Method 'PATCH' (atau 'PUT') wajib untuk update --}}
                        
                        {{-- Nama Kelas --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $class->name) }}" {{-- Tampilkan data lama --}}
                                   required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        
                        {{-- Dropdown Wali Kelas --}}
                        <div class="mb-4">
                            <label for="homeroom_teacher_id" class="block text-sm font-medium text-gray-700">Wali Kelas (Opsional)</label>
                            <select name="homeroom_teacher_id" id="homeroom_teacher_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" 
                                        {{-- Pilih Wali Kelas yang sudah tersimpan --}}
                                        {{ old('homeroom_teacher_id', $class->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('classes.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>