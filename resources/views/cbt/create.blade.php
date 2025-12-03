<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Jadwal Ujian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <h3 class="text-lg font-bold text-slate-800 mb-6">Informasi Ujian Baru</h3>

                <form action="{{ route('cbt.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Judul Ujian -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama/Judul Ujian</label>
                        <input type="text" name="title" required class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: PTS Matematika Semester 1">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mapel -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
                            <input type="text" name="subject_name" required class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <!-- Kelas -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tingkat Kelas</label>
                            <select name="class_level" class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="7">Kelas 7</option>
                                <option value="8">Kelas 8</option>
                                <option value="9">Kelas 9</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Waktu Mulai -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Mulai</label>
                            <input type="datetime-local" name="start_time" required class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <!-- Waktu Selesai -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Selesai</label>
                            <input type="datetime-local" name="end_time" required class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Durasi -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Durasi (Menit)</label>
                            <input type="number" name="duration_minutes" value="90" required class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <!-- KKM -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Passing Grade (KKM)</label>
                            <input type="number" name="passing_grade" value="75" required class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                         <!-- Token -->
                         <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Token Ujian (Opsional)</label>
                            <input type="text" name="token" maxlength="6" class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 uppercase" placeholder="XJ9KL">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-4">
                        <input type="checkbox" name="is_active" value="1" checked id="active" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <label for="active" class="text-sm text-slate-700">Aktifkan ujian segera setelah disimpan</label>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('cbt.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 rounded-lg text-slate-700 font-bold hover:bg-slate-50 transition">Batal</a>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Simpan Jadwal</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>