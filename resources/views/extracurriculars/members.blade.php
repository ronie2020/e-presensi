<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Peserta Ekstrakurikuler</h2>
                <p class="text-gray-500 text-sm">Kelola siswa yang mengikuti kegiatan.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- FILTER EKSKUL -->
                <form method="GET" action="{{ route('extracurriculars.members') }}" class="mb-8 flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kegiatan Ekskul</label>
                        <select name="ekskul_id" onchange="this.form.submit()" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Ekskul --</option>
                            @foreach($extracurriculars as $ekskul)
                                <option value="{{ $ekskul->id }}" {{ $selectedEkskulId == $ekskul->id ? 'selected' : '' }}>
                                    {{ $ekskul->name }} ({{ $ekskul->members_count ?? 0 }} Anggota)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                @if($selectedEkskulId)
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Daftar Anggota</h3>
                        <!-- Form Tambah Siswa -->
                        <form action="{{ route('extracurriculars.members.store') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="hidden" name="extracurricular_id" value="{{ $selectedEkskulId }}">
                            <select name="student_id" required class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" style="min-width: 300px;">
                                <option value="">+ Pilih Siswa untuk Ditambahkan</option>
                                @foreach($students as $s)
                                    <!-- PERBAIKAN: Menampilkan Kelas dari Relasi schoolClass -->
                                    <option value="{{ $s->student_id }}">
                                        {{ $s->schoolClass->name ?? $s->class_name ?? 'Tanpa Kelas' }} - {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700">Tambah</button>
                        </form>
                    </div>

                    <!-- Tabel Anggota -->
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($members as $member)
                                <tr>
                                    <!-- PERBAIKAN: Menampilkan Kelas -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                        {{ $member->student->schoolClass->name ?? $member->student->class_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $member->student->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <form action="{{ route('extracurriculars.members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Keluarkan siswa ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Keluarkan</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada anggota di ekskul ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-gray-500">Silakan pilih ekstrakurikuler di atas untuk melihat dan mengelola anggota.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>