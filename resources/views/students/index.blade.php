{{-- Halaman ini adalah tampilan untuk resources/views/students/index.blade.php --}}
<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header Page --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                Manajemen Data Siswa
            </h1>
            <p class="text-gray-500 mt-1">
                Kelola data induk siswa, kartu RFID, dan informasi orang tua.
            </p>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-400 hover:text-rose-600">&times;</button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- KOLOM KIRI: FORM INPUT --}}
            <div class="lg:col-span-1 space-y-8">
                
                <!-- CARD 1: TAMBAH SISWA -->
                <div class="bg-white rounded-3xl shadow-sm border border-violet-100 overflow-hidden relative group hover:shadow-lg hover:shadow-violet-100/50 transition-all duration-300 h-fit">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-violet-500"></div>
                    <div class="p-6 md:p-8 relative z-10">
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-violet-50 text-violet-600 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-violet-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-800">Siswa Baru</h3>
                                <p class="text-xs text-gray-500">Daftarkan siswa manual</p>
                            </div>
                        </div>

                        <form action="{{ route('students.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">ID Siswa (NISN)</label>
                                <input type="text" name="student_id" value="{{ old('student_id') }}" required 
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-3 font-bold text-gray-700 transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}" required 
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-3 font-bold text-gray-700 transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kelas</label>
                                <div class="relative">
                                    <select name="class_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-3 font-medium transition-colors appearance-none">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
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
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">ID RFID (Opsional)</label>
                                    <input type="text" name="rfid_id" value="{{ old('rfid_id') }}" 
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-3 font-mono transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">WA Orang Tua</label>
                                    <input type="text" name="parent_wa_number" value="{{ old('parent_wa_number') }}" placeholder="628..."
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-3 font-mono transition-colors">
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 px-6 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 transition-all shadow-lg shadow-violet-200 flex items-center justify-center gap-2 mt-2 group-hover:translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Simpan Siswa
                            </button>
                        </form>
                    </div>
                </div>

                <!-- CARD 2: IMPORT DATA -->
                <div class="bg-gradient-to-br from-indigo-50 to-violet-50 rounded-3xl border border-violet-100 p-6 md:p-8 relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-lg font-black text-gray-800 mb-2">Import Massal</h3>
                        <p class="text-xs text-gray-500 mb-4">Upload file CSV/Excel untuk input banyak data sekaligus.</p>
                        
                        <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="relative mb-4 group">
                                <input type="file" name="file" id="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                <div class="bg-white border-2 border-dashed border-violet-200 rounded-xl p-4 text-center transition-all group-hover:border-violet-400 group-hover:bg-white/80">
                                    <svg class="w-8 h-8 text-violet-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <span class="text-xs font-bold text-violet-600">Klik untuk pilih file</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mb-4">*Header: studentid, nama, kelas, nomorwa, rfidid</p>
                            
                            <button type="submit" class="w-full py-2.5 px-4 bg-white border border-violet-200 text-violet-700 font-bold rounded-xl hover:bg-violet-50 transition-colors text-sm">
                                Upload File
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: DAFTAR SISWA --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col sticky top-6">
                    
                    {{-- Toolbar --}}
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-black text-gray-800">Daftar Siswa</h3>
                            <span class="text-xs font-bold bg-white px-2 py-1 rounded border border-gray-200 text-gray-500">{{ $students->total() }} Data Total</span>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                            <form action="{{ route('students.index') }}" method="GET" class="flex gap-2 flex-1">
                                <div class="relative flex-1">
                                    <input type="text" name="search" placeholder="Cari nama / ID..." value="{{ request('search') }}"
                                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border-gray-200 bg-white focus:border-violet-500 focus:ring-violet-500 text-sm shadow-sm">
                                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                
                                <select name="filter_class_id" onchange="this.form.submit()" class="rounded-xl border-gray-200 bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-2.5 shadow-sm">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('filter_class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <a href="{{ route('students.export') }}" class="flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors shadow-sm font-bold text-sm gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Excel
                            </a>
                        </div>
                    </div>
                    
                    {{-- Table --}}
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full text-left border-collapse">
                            <thead class="bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Identitas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Info Kontak</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                                </div>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($students as $student)
                                    <tr class="hover:bg-violet-50/30 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gray-100 text-gray-500 font-bold flex items-center justify-center text-xs group-hover:bg-violet-100 group-hover:text-violet-600 transition-colors uppercase">
                                                    {{ substr($student->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-800 text-sm">{{ $student->name }}</div>
                                                    <div class="text-xs text-gray-400 font-mono">{{ $student->student_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200 group-hover:bg-white group-hover:border-violet-200 group-hover:text-violet-700 transition-colors">
                                                {{ $student->schoolClass->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($student->parent_wa_number)
                                                <div class="flex items-center text-xs text-gray-500 gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                    <span class="font-mono">{{ $student->parent_wa_number }}</span>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-300 italic">No WA -</span>
                                            @endif
                                            @if($student->rfid_id)
                                                <div class="text-[10px] text-gray-400 mt-1 font-mono flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.2-2.85.577-4.147l.104-.194c.693-1.287 1.523-2.491 2.462-3.599"></path></svg>
                                                    RFID Set
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-1">
                                                {{-- Edit --}}
                                                <a href="{{ route('students.edit', $student->id) }}" class="p-2 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                
                                                {{-- Absen Manual --}}
                                                <button type="button"
                                                   data-student-id="{{ $student->id }}"
                                                   data-student-name="{{ $student->name }}"
                                                   class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors open-absen-modal" title="Absen Manual">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                </button>

                                                {{-- QR Code --}}
                                                <button type="button"
                                                   data-student-id="{{ $student->student_id }}"
                                                   data-student-name="{{ $student->name }}"
                                                   class="p-2 text-gray-400 hover:text-green-500 hover:bg-green-50 rounded-lg transition-colors open-qr-modal" title="QR Code">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 19v-4H4v4h2zM6 12V7a1 1 0 011-1h10a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                </button>

                                                {{-- Delete --}}
                                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Hapus siswa ini?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-gray-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                </div>
                                                <p class="text-sm font-medium">Belum ada data siswa.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="p-4 border-t border-gray-100">
                        {{ $students->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ABSEN MANUAL (MODERN) --}}
    <div id="absen-manual-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-10 mx-auto p-0 border-0 w-full max-w-lg shadow-2xl rounded-2xl bg-white overflow-hidden">
            <div class="bg-violet-600 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white text-lg">Input Absensi Manual</h3>
                <button type="button" id="absen-modal-close" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>

            <form id="absen-manual-form" action="{{ route('reports.storeManual') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="student_id" id="absen-modal-student-id">
                <input type="hidden" name="attendance_type" value="Harian">

                <div class="mb-6">
                    <p class="text-sm text-gray-500 mb-1">Siswa</p>
                    <p id="absen-modal-student-name" class="text-lg font-bold text-gray-800">Nama Siswa</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tanggal</label>
                        <input type="date" name="date" id="absen-tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-violet-500 focus:border-violet-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Status</label>
                        <select name="status" id="absen-status" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-violet-500 focus:border-violet-500" onchange="toggleTimeInput()">
                            <option value="Hadir">Hadir</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Alfa">Alfa</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100" id="time-input-container">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Masuk</label>
                            <input type="time" name="time_in" id="absen-time-in" class="block w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pulang</label>
                            <input type="time" name="time_out" id="absen-time-out" class="block w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Catatan</label>
                        <textarea name="notes" id="absen-notes" rows="2" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-violet-500 focus:border-violet-500"></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" id="absen-modal-cancel" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL QR CODE (MODERN) --}}
    <div id="qr-code-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-sm shadow-2xl rounded-2xl bg-white overflow-hidden">
            <div class="p-8 text-center">
                <h3 class="text-lg font-black text-gray-800 mb-1" id="qr-modal-student-name">QR Code Siswa</h3>
                <p class="text-xs text-gray-400 uppercase tracking-widest mb-6">Kartu Identitas Digital</p>
                
                <div class="bg-white p-4 border-2 border-dashed border-violet-200 rounded-2xl inline-block mb-6">
                    <img id="qr-modal-image" src="" alt="QR Code" class="w-48 h-48 object-contain">
                </div>
                
                <div class="flex gap-3 justify-center">
                    <button type="button" id="qr-modal-close" class="px-4 py-2 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200">Tutup</button>
                    <a id="qr-modal-download" href="#" download="qrcode.png" class="px-4 py-2 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 shadow-lg shadow-violet-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTimeInput() {
            const status = document.getElementById('absen-status').value;
            const timeContainer = document.getElementById('time-input-container');
            
            if (status === 'Hadir') {
                timeContainer.classList.remove('hidden', 'opacity-50', 'pointer-events-none');
            } else {
                timeContainer.classList.add('hidden', 'opacity-50', 'pointer-events-none');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Absen Manual Modal
            const absenModal = document.getElementById('absen-manual-modal');
            const absenModalName = document.getElementById('absen-modal-student-name');
            const absenModalInputId = document.getElementById('absen-modal-student-id');
            const absenModalTimeIn = document.getElementById('absen-time-in');
            
            document.querySelectorAll('.open-absen-modal').forEach(button => {
                button.addEventListener('click', function() {
                    absenModalName.textContent = this.dataset.studentName;
                    absenModalInputId.value = this.dataset.studentId;
                    document.getElementById('absen-status').value = 'Hadir';
                    toggleTimeInput();
                    
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    absenModalTimeIn.value = `${hours}:${minutes}`;
                    
                    absenModal.classList.remove('hidden');
                });
            });

            const closeAbsenFn = () => absenModal.classList.add('hidden');
            document.getElementById('absen-modal-close').addEventListener('click', closeAbsenFn);
            document.getElementById('absen-modal-cancel').addEventListener('click', closeAbsenFn);

            // QR Code Modal
            const qrModal = document.getElementById('qr-code-modal');
            const qrName = document.getElementById('qr-modal-student-name');
            const qrImage = document.getElementById('qr-modal-image');
            const qrDownload = document.getElementById('qr-modal-download');

            document.querySelectorAll('.open-qr-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.studentId;
                    const name = this.dataset.studentName;
                    const url = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(id)}`;
                    
                    qrName.textContent = name;
                    qrImage.src = url;
                    qrDownload.href = url;
                    qrDownload.download = `QR_${name.replace(/\s+/g, '_')}.png`;
                    
                    qrModal.classList.remove('hidden');
                });
            });

            document.getElementById('qr-modal-close').addEventListener('click', () => qrModal.classList.add('hidden'));
        });
    </script>
</x-app-layout>