<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Ekstrakurikuler</h2>
                <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Tambah Ekskul
                </button>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ikon / Gambar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Ekskul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembina</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($extracurriculars as $ekskul)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 text-xl overflow-hidden border border-blue-100">
                                            @if(Str::startsWith($ekskul->icon, 'storage/'))
                                                <img src="{{ asset($ekskul->icon) }}" alt="Icon" class="w-full h-full object-cover">
                                            @elseif(Str::startsWith($ekskul->icon, 'http'))
                                                 <img src="{{ $ekskul->icon }}" alt="Icon" class="w-full h-full object-cover">
                                            @else
                                                <i class="{{ $ekskul->icon ?? 'ph-fill ph-star' }}"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $ekskul->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $ekskul->coach_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $ekskul->schedule ?? 'Belum diatur' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        {{ $ekskul->members_count }} Siswa
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Tombol Edit -->
                                            <button type="button" 
                                                onclick="openEditModal({{ json_encode($ekskul) }})"
                                                class="text-yellow-600 hover:text-yellow-900 font-bold">
                                                Edit
                                            </button>
                                            
                                            <span class="text-gray-300">|</span>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('extracurriculars.destroy', $ekskul->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data ekstrakurikuler.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="document.getElementById('addModal').classList.add('hidden')"></div>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('extracurriculars.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Ekstrakurikuler Baru</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Ekskul</label>
                                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Pembina</label>
                                    <input type="text" name="coach_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jadwal</label>
                                    <input type="text" name="schedule" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Senin, 15:00">
                                </div>
                            </div>
                            <div class="border-t pt-3 mt-2">
                                <label class="block text-sm font-bold text-gray-800 mb-2">Tampilan (Pilih Salah Satu)</label>
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Opsi A: Upload Gambar</label>
                                    <input type="file" name="image_file" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                </div>
                                <div class="relative flex py-2 items-center">
                                    <div class="flex-grow border-t border-gray-200"></div><span class="flex-shrink-0 mx-4 text-xs text-gray-400 font-bold">ATAU</span><div class="flex-grow border-t border-gray-200"></div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Opsi B: Kode Ikon (Phosphor)</label>
                                    <input type="text" name="icon_text" placeholder="ph-fill ph-basketball" class="flex-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm font-mono">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="document.getElementById('editModal').classList.add('hidden')"></div>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Form Action akan diupdate via JS -->
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf 
                    @method('PUT')
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Edit Ekstrakurikuler</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Ekskul</label>
                                <input type="text" name="name" id="edit_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Pembina</label>
                                    <input type="text" name="coach_name" id="edit_coach" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jadwal</label>
                                    <input type="text" name="schedule" id="edit_schedule" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- BAGIAN EDIT IKON -->
                            <div class="border-t pt-3 mt-2">
                                <label class="block text-sm font-bold text-gray-800 mb-2">Update Tampilan (Opsional)</label>
                                <p class="text-xs text-gray-500 mb-2">Biarkan kosong jika tidak ingin mengubah ikon/gambar.</p>
                                
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Upload Gambar Baru</label>
                                    <input type="file" name="image_file" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                </div>
                                <div class="relative flex py-2 items-center">
                                    <div class="flex-grow border-t border-gray-200"></div><span class="flex-shrink-0 mx-4 text-xs text-gray-400 font-bold">ATAU</span><div class="flex-grow border-t border-gray-200"></div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Ganti Kode Ikon</label>
                                    <input type="text" name="icon_text" id="edit_icon_text" class="flex-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm font-mono">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Update</button>
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(ekskul) {
            // Isi form dengan data yang dikirim
            document.getElementById('edit_name').value = ekskul.name;
            document.getElementById('edit_coach').value = ekskul.coach_name;
            document.getElementById('edit_schedule').value = ekskul.schedule;
            
            // Cek apakah icon adalah text (bukan path storage)
            if (ekskul.icon && !ekskul.icon.startsWith('storage/')) {
                document.getElementById('edit_icon_text').value = ekskul.icon;
            } else {
                document.getElementById('edit_icon_text').value = ''; // Reset jika gambar
            }

            // [PERBAIKAN UTAMA] Gunakan dummy ID '0' lalu replace dengan ID asli
            let url = "{{ route('extracurriculars.update', 0) }}";
            let form = document.getElementById('editForm');
            form.action = url.replace('/0', '/' + ekskul.id);

            // Tampilkan Modal
            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>
</x-app-layout>