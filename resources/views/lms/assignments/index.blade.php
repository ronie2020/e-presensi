<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tugas & PR') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">📝 Manajemen Tugas</h2>
                    <p class="text-sm text-gray-500">Buat tugas dan nilai pekerjaan siswa.</p>
                </div>
                <a href="{{ route('lms.assignments.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Tugas Baru
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    @if($assignments->count() > 0)
                        <div class="grid gap-6">
                            @foreach($assignments as $task)
                                <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 border border-gray-200 rounded-xl hover:border-blue-300 transition group">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            <h3 class="font-bold text-lg text-gray-800">{{ $task->title }}</h3>
                                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-700">
                                                {{ $task->schoolClass->name ?? 'Kelas' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mb-2">
                                            {{ $task->subject->name }} • Deadline: <span class="text-red-600 font-semibold">{{ $task->deadline->format('d M Y, H:i') }}</span>
                                        </p>
                                        <div class="flex items-center gap-4 text-xs text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                {{ $task->submissions_count }} Mengumpulkan
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-4 md:mt-0 flex items-center gap-2">
                                        <!-- Tombol Periksa -->
                                        <a href="{{ route('lms.assignments.submissions', $task->id) }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg font-medium hover:bg-indigo-100 transition flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                            Periksa / Nilai
                                        </a>

                                        <!-- Hapus -->
                                        <form action="{{ route('lms.assignments.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini? Data nilai siswa juga akan hilang.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $assignments->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500">Belum ada tugas yang dibuat.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>