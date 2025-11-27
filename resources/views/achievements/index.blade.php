<x-app-layout>
    {{-- Load Library Scanner (Opsional jika ingin scan kartu siswa) --}}
    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    @endpush

    <div class="py-6 sm:py-8">
        
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                Prestasi & Penghargaan
            </h1>
            <p class="text-gray-500 mt-1">
                Dokumentasikan pencapaian gemilang siswa, guru, dan sekolah.
            </p>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false">&times;</button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- FORM INPUT PRESTASI --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-yellow-100 overflow-hidden sticky top-6">
                    <div class="p-6 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-yellow-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-800">Input Prestasi</h3>
                                <p class="text-xs text-gray-500">Catat momen juara baru</p>
                            </div>
                        </div>

                        <form action="{{ route('achievements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="{ type: 'Siswa' }">
                            @csrf
                            
                            {{-- Pilih Tipe --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Siapa yang berprestasi?</label>
                                <div class="flex rounded-xl bg-gray-50 p-1 border border-gray-200">
                                    <button type="button" @click="type = 'Siswa'" :class="type === 'Siswa' ? 'bg-white text-yellow-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-2 rounded-lg text-xs font-bold transition-all">Siswa</button>
                                    <button type="button" @click="type = 'Guru'" :class="type === 'Guru' ? 'bg-white text-yellow-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-2 rounded-lg text-xs font-bold transition-all">Guru</button>
                                    <button type="button" @click="type = 'Sekolah'" :class="type === 'Sekolah' ? 'bg-white text-yellow-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-2 rounded-lg text-xs font-bold transition-all">Sekolah</button>
                                </div>
                                <input type="hidden" name="type" x-model="type">
                            </div>

                            {{-- Input Nama Dynamic --}}
                            <div x-show="type === 'Siswa'">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Siswa</label>
                                <select name="student_id" class="w-full rounded-xl border-gray-200 focus:border-yellow-500 focus:ring-yellow-500 text-sm">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->schoolClass->name ?? '-' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div x-show="type !== 'Siswa'" style="display: none;">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Guru / Tim</label>
                                <input type="text" name="name_manual" placeholder="Contoh: Tim Futsal Guru" class="w-full rounded-xl border-gray-200 focus:border-yellow-500 focus:ring-yellow-500 text-sm">
                            </div>

                            {{-- Judul Prestasi --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Judul Kejuaraan</label>
                                <input type="text" name="title" required placeholder="Contoh: Juara 1 Lomba Pidato" class="w-full rounded-xl border-gray-200 focus:border-yellow-500 focus:ring-yellow-500 text-sm font-bold text-gray-700">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tingkat</label>
                                    <select name="level" class="w-full rounded-xl border-gray-200 focus:border-yellow-500 focus:ring-yellow-500 text-sm">
                                        <option value="Sekolah">Sekolah</option>
                                        <option value="Kecamatan">Kecamatan</option>
                                        <option value="Kabupaten">Kabupaten</option>
                                        <option value="Provinsi">Provinsi</option>
                                        <option value="Nasional">Nasional</option>
                                        <option value="Internasional">Internasional</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tanggal</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-gray-200 focus:border-yellow-500 focus:ring-yellow-500 text-sm">
                                </div>
                            </div>

                            {{-- Upload Media --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Foto Dokumentasi</label>
                                <input type="file" name="photo" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 border border-gray-200 rounded-xl">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Link Video (Youtube/IG)</label>
                                <input type="url" name="video_link" placeholder="https://..." class="w-full rounded-xl border-gray-200 focus:border-yellow-500 focus:ring-yellow-500 text-sm">
                            </div>

                            <button type="submit" class="w-full py-3 px-4 bg-yellow-500 text-white font-bold rounded-xl hover:bg-yellow-600 transition-all shadow-lg shadow-yellow-200 flex items-center justify-center gap-2 mt-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Simpan Prestasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- DAFTAR PRESTASI --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Prestasi Sekolah</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-100 text-xs font-bold text-gray-400 uppercase">
                                    <th class="py-3 px-4">Tanggal</th>
                                    <th class="py-3 px-4">Juara</th>
                                    <th class="py-3 px-4">Prestasi</th>
                                    <th class="py-3 px-4">Tingkat</th>
                                    <th class="py-3 px-4 text-right">Aksi</th>
                                </div>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($achievements as $item)
                                    <tr class="hover:bg-yellow-50/30 transition group">
                                        <td class="py-3 px-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="font-bold text-gray-800 text-sm">{{ $item->achiever_name }}</div>
                                            <div class="text-xs text-gray-400">{{ $item->type }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="font-medium text-indigo-600 text-sm">{{ $item->title }}</div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                                {{ $item->level }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <form action="{{ route('achievements.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                                @csrf @method('DELETE')
                                                <button class="text-gray-400 hover:text-rose-500 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-400 text-sm">Belum ada data prestasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $achievements->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>