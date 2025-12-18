<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penilaian Tugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Informasi Tugas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $assignment->title }}</h2>
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wide">
                                    {{ $assignment->schoolClass->name }}
                                </span>
                            </div>
                            <p class="text-gray-500 text-sm">
                                Mapel: <span class="font-medium text-gray-700">{{ $assignment->subject->name }}</span> • 
                                Deadline: <span class="font-medium text-red-600">{{ $assignment->deadline->format('d M Y, H:i') }}</span> •
                                Tipe: <span class="font-medium text-blue-600 uppercase">{{ str_replace('_', ' ', $assignment->assignment_type) }}</span>
                            </p>
                        </div>
                        <a href="{{ route('lms.assignments.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-500 hover:text-green-700">&times;</button>
                </div>
            @endif

            <!-- Tabel Daftar Siswa -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Daftar Pengumpulan Siswa</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Jawaban Siswa</th>
                                    <th class="px-6 py-4">Waktu Kirim</th>
                                    <th class="px-6 py-4 text-center">Nilai (0-100)</th>
                                    <th class="px-6 py-4">Feedback</th>
                                    <th class="px-6 py-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($allStudents as $student)
                                    @php
                                        // Cari submission
                                        $submission = $submissions->where('student_id', $student->id)->first();
                                        
                                        // Cek status terlambat
                                        $isLate = false;
                                        if($submission && $submission->submitted_at > $assignment->deadline) {
                                            $isLate = true;
                                        }
                                    @endphp

                                    <tr class="bg-white hover:bg-gray-50 transition">
                                        <!-- Nama Siswa -->
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $student->name }}
                                            <div class="text-xs text-gray-400">{{ $student->nisn }}</div>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4">
                                            @if($submission)
                                                @if($isLate)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                        Terlambat
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Sudah Kumpul
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Belum
                                                </span>
                                            @endif
                                        </td>

                                        <!-- File Jawaban (DIPERBAIKI) -->
                                        <td class="px-6 py-4">
                                            @if($submission)
                                                {{-- KASUS 1: Ada File Fisik --}}
                                                @if($submission->file_path)
                                                    <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 font-bold">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        Lihat File
                                                    </a>
                                                {{-- KASUS 2: Tidak Ada File (Tugas Link/Teks) --}}
                                                @else
                                                    <span class="text-gray-500 italic text-xs block mb-1">Via Teks/Link:</span>
                                                @endif

                                                {{-- Tampilkan Catatan Siswa (Penting untuk tugas Link) --}}
                                                @if($submission->student_note)
                                                    <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded border border-gray-200 mt-1 max-w-[200px]">
                                                        "{{ Str::limit($submission->student_note, 50) }}"
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <!-- Waktu Kirim -->
                                        <td class="px-6 py-4 text-xs">
                                            @if($submission)
                                                {{ $submission->submitted_at->format('d M H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <!-- Form Penilaian -->
                                        @if($submission)
                                            <form action="{{ route('lms.submissions.grade', $submission->id) }}" method="POST">
                                                @csrf
                                                
                                                <!-- Kolom Input Nilai -->
                                                <td class="px-6 py-4 text-center">
                                                    <input type="number" name="grade" min="0" max="100" 
                                                           value="{{ $submission->grade }}" 
                                                           class="w-20 text-center rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm font-bold text-gray-700"
                                                           placeholder="0-100">
                                                </td>

                                                <!-- Kolom Input Feedback -->
                                                <td class="px-6 py-4">
                                                    <input type="text" name="feedback" 
                                                           value="{{ $submission->teacher_feedback }}" 
                                                           class="w-full min-w-[150px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                           placeholder="Beri masukan...">
                                                </td>

                                                <!-- Tombol Simpan -->
                                                <td class="px-6 py-4">
                                                    <button type="submit" class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition shadow-sm" title="Simpan Nilai">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </td>
                                            </form>
                                        @else
                                            <!-- Siswa belum mengumpulkan: Tidak bisa dinilai di halaman ini -->
                                            <td colspan="3" class="px-6 py-4 text-center text-xs text-gray-400 italic">
                                                Menunggu pengumpulan...
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($allStudents->count() == 0)
                        <div class="text-center py-8 text-gray-500">
                            Tidak ada siswa di kelas ini.
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>