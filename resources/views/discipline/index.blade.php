{{-- Halaman ini adalah tampilan untuk resources/views/discipline/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catatan Disiplin Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Tampilkan pesan sukses jika ada --}}
            @if (session('success'))
                <div class="p-4 mb-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tampilkan error validasi jika ada --}}
            @if ($errors->any())
                <div class="p-4 mb-4 bg-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Grid untuk Dua Form (Pelanggaran & Kebaikan) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- 1. Form Tambah Catatan Pelanggaran (Ungu) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Tambah Catatan Pelanggaran</h3>
                        <form action="{{ route('discipline.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="date" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            
                            <div class="mb-4">
                                <label for="student_id_violation" class="block text-sm font-medium text-gray-700">Pilih Siswa</label>
                                <select name="student_id" id="student_id_violation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->schoolClass->name ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="discipline_type_id_violation" class="block text-sm font-medium text-gray-700">Jenis Pelanggaran</label>
                                <select name="discipline_type_id" id="discipline_type_id_violation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih Jenis Pelanggaran --</option>
                                    @foreach ($violationTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('discipline_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }} ({{ $type->point_value }} Poin)</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes_violation" class="block text-sm font-medium text-gray-700">Detail Kejadian (Opsional)</label>
                                <textarea name="notes" id="notes_violation" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes') }}</textarea>
                            </div>

                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Simpan Catatan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- 2. Form Tambah Catatan Kebaikan (Hijau) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Tambah Catatan Kebaikan</h3>
                        <form action="{{ route('discipline.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="date" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            
                            <div class="mb-4">
                                <label for="student_id_merit" class="block text-sm font-medium text-gray-700">Pilih Siswa</label>
                                <select name="student_id" id="student_id_merit" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->schoolClass->name ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="discipline_type_id_merit" class="block text-sm font-medium text-gray-700">Jenis Kebaikan</label>
                                <select name="discipline_type_id" id="discipline_type_id_merit" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih Jenis Kebaikan --</option>
                                    @foreach ($meritTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('discipline_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }} (+{{ $type->point_value }} Poin)</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes_merit" class="block text-sm font-medium text-gray-700">Detail Kejadian (Opsional)</label>
                                <textarea name="notes" id="notes_merit" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes') }}</textarea>
                            </div>

                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Simpan Catatan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 3. Tabel Ringkasan Poin (Log) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Ringkasan Poin (Berdasarkan Catatan Terakhir)</h3>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kejadian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dicatat Oleh</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($records as $record)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $record->student->name ?? 'Siswa Dihapus' }}</div>
                                            <div class="text-sm text-gray-500">{{ $record->student->schoolClass->name ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $record->disciplineType->name ?? 'Tipe Dihapus' }}</div>
                                            <div class="text-sm text-gray-500">{{ $record->notes ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $record->disciplineType->point_value < 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $record->disciplineType->point_value > 0 ? '+' : '' }}{{ $record->disciplineType->point_value ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $record->recorder->name ?? 'User Dihapus' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <form action="{{ route('discipline.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Belum ada catatan disiplin.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination Links --}}
                    <div class="mt-4">
                        {{ $records->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>