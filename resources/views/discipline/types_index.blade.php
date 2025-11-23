<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Master Disiplin') }}
            </h2>
            <a href="{{ route('discipline.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600">
                &larr; Kembali ke Catatan Disiplin
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Pesan Sukses/Error --}}
            @if (session('success'))
                <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- FORM TAMBAH DATA BARU -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Tambah Jenis Baru</h3>
                    <form action="{{ route('discipline-types.store') }}" method="POST" class="flex flex-wrap gap-4 items-end">
                        @csrf
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700">Nama (Contoh: Terlambat)</label>
                            <input type="text" name="name" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="w-40">
                            <label class="block text-sm font-medium text-gray-700">Tipe</label>
                            <select name="type" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Pelanggaran">Pelanggaran</option>
                                <option value="Kebaikan">Kebaikan</option>
                            </select>
                        </div>
                        <div class="w-32">
                            <label class="block text-sm font-medium text-gray-700">Poin Default</label>
                            <input type="number" name="point_value" required min="1" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 mb-[2px]">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- TABEL PELANGGARAN -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-500">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4 text-red-700">Daftar Jenis Pelanggaran</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pelanggaran</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Poin</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($violationTypes as $item)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $item->name }}</td>
                                            <td class="px-4 py-2 text-sm text-center font-bold text-red-600">{{ $item->point_value }}</td>
                                            <td class="px-4 py-2 text-center">
                                                <form action="{{ route('discipline-types.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TABEL KEBAIKAN -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-green-500">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4 text-green-700">Daftar Jenis Kebaikan</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Kebaikan</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Poin</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($meritTypes as $item)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $item->name }}</td>
                                            <td class="px-4 py-2 text-sm text-center font-bold text-green-600">+{{ $item->point_value }}</td>
                                            <td class="px-4 py-2 text-center">
                                                <form action="{{ route('discipline-types.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>