<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header --}}
        <div class="mb-8 px-4 sm:px-0">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                <i class="ph-duotone ph-users-three text-blue-600"></i> Peserta Ekstrakurikuler
            </h1>
            <p class="text-slate-500 mt-2 text-lg">
                Kelola keanggotaan siswa dalam setiap kegiatan.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 sm:px-0 items-start">
            
            {{-- KOLOM KIRI (FILTER & NAVIGASI) --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden sticky top-24">
                    <div class="p-6 bg-slate-50 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800">Pilih Kegiatan</h3>
                        <p class="text-xs text-slate-500">Pilih ekskul untuk melihat anggota.</p>
                    </div>
                    <div class="p-4">
                        <form method="GET" action="{{ route('extracurriculars.members') }}">
                            <div class="space-y-2">
                                @foreach($extracurriculars as $ekskul)
                                    <button type="submit" name="ekskul_id" value="{{ $ekskul->id }}" 
                                        class="w-full flex items-center justify-between p-3 rounded-xl transition-all {{ $selectedEkskulId == $ekskul->id ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'bg-white hover:bg-slate-50 text-slate-600 border border-slate-100' }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $selectedEkskulId == $ekskul->id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
                                                <i class="{{ $ekskul->icon && !Str::startsWith($ekskul->icon, 'storage') ? $ekskul->icon : 'ph-fill ph-star' }}"></i>
                                            </div>
                                            <span class="font-bold text-sm">{{ $ekskul->name }}</span>
                                        </div>
                                        <span class="text-xs font-bold px-2 py-1 rounded-full {{ $selectedEkskulId == $ekskul->id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $ekskul->members_count }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (DATA ANGGOTA) --}}
            <div class="lg:col-span-2 space-y-6">
                @if($selectedEkskulId)
                    <!-- Form Tambah Anggota -->
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i class="ph-bold ph-user-plus text-blue-500"></i> Tambah Anggota Baru
                        </h3>
                        <form action="{{ route('extracurriculars.members.store') }}" method="POST" class="flex gap-3 items-center">
                            @csrf
                            <input type="hidden" name="extracurricular_id" value="{{ $selectedEkskulId }}">
                            <div class="relative flex-1">
                                <select name="student_id" required class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                    <option value="">-- Cari Nama Siswa --</option>
                                    @foreach($students as $s)
                                        <option value="{{ $s->student_id }}">
                                            {{ $s->schoolClass->name ?? 'N/A' }} — {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 text-sm flex items-center gap-2">
                                <i class="ph-bold ph-plus"></i> Tambah
                            </button>
                        </form>
                    </div>

                    <!-- Tabel Anggota -->
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                            <h3 class="font-bold text-slate-800">Daftar Anggota Aktif</h3>
                            <span class="bg-slate-100 text-xs font-bold px-3 py-1 rounded-full text-slate-500">{{ count($members) }} Siswa</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase">
                                    <tr>
                                        <th class="px-6 py-4">Siswa</th>
                                        <th class="px-6 py-4 text-center">Kelas</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($members as $member)
                                        <tr class="hover:bg-slate-50/50 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                                        {{ substr($member->student->name, 0, 2) }}
                                                    </div>
                                                    <span class="font-bold text-slate-700 text-sm">{{ $member->student->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-bold text-slate-600">
                                                    {{ $member->student->schoolClass->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <form action="{{ route('extracurriculars.members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Keluarkan siswa ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-slate-300 hover:text-rose-600 transition-colors p-2 rounded-lg hover:bg-rose-50" title="Keluarkan">
                                                        <i class="ph-bold ph-sign-out text-lg"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                                    <i class="ph-duotone ph-users text-3xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-600">Belum ada anggota.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-64 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 text-center px-4">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 text-slate-300 shadow-sm">
                            <i class="ph-duotone ph-hand-pointing text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-600">Pilih Kegiatan Dahulu</h3>
                        <p class="text-sm text-slate-400 max-w-xs mt-1">Silakan pilih salah satu ekstrakurikuler di menu sebelah kiri untuk mengelola anggotanya.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>