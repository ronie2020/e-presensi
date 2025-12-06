<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Jadwal Ujian') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl md:rounded-lg p-5 md:p-8">
                
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b border-slate-100 pb-2">Informasi Ujian Baru</h3>

                <form action="{{ route('cbt.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <!-- Judul Ujian -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama/Judul Ujian</label>
                        <input type="text" name="title" required class="w-full rounded-xl border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 md:py-2" placeholder="Contoh: PTS Matematika Semester 1">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Mapel -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
                            <input type="text" name="subject_name" required class="w-full rounded-xl border-slate-300 shadow-sm py-3 md:py-2">
                        </div>
                        
                        <!-- Kelas -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tingkat Kelas</label>
                            <select name="class_level" class="w-full rounded-xl border-slate-300 shadow-sm py-3 md:py-2 bg-white">
                                <option value="7">Kelas 7</option>
                                <option value="8">Kelas 8</option>
                                <option value="9">Kelas 9</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Waktu Mulai -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Mulai</label>
                            <input type="datetime-local" name="start_time" required class="w-full rounded-xl border-slate-300 shadow-sm py-3 md:py-2">
                        </div>
                        
                        <!-- Waktu Selesai -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Selesai</label>
                            <input type="datetime-local" name="end_time" required class="w-full rounded-xl border-slate-300 shadow-sm py-3 md:py-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                        <!-- Durasi -->
                        <div class="col-span-1">
                            <label class="block text-xs md:text-sm font-medium text-slate-700 mb-1">Durasi (Menit)</label>
                            <input type="number" name="duration_minutes" value="90" required class="w-full rounded-xl border-slate-300 shadow-sm py-3 md:py-2">
                        </div>
                        
                        <!-- KKM -->
                        <div class="col-span-1">
                            <label class="block text-xs md:text-sm font-medium text-slate-700 mb-1">KKM / Kriteria</label>
                            <input type="number" name="passing_grade" value="75" required class="w-full rounded-xl border-slate-300 shadow-sm py-3 md:py-2">
                        </div>

                         <!-- Token -->
                         <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Token (Opsional)</label>
                            <input type="text" name="token" maxlength="6" class="w-full rounded-xl border-slate-300 shadow-sm uppercase font-mono tracking-widest py-3 md:py-2" placeholder="AUTO">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <input type="checkbox" name="is_active" value="1" checked id="active" class="w-5 h-5 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <label for="active" class="text-sm font-bold text-slate-700">Aktifkan ujian segera</label>
                    </div>

                    <div class="flex flex-col-reverse md:flex-row justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('cbt.index') }}" class="w-full md:w-auto text-center px-5 py-3 md:py-2.5 bg-white border border-slate-300 rounded-xl text-slate-700 font-bold hover:bg-slate-50 transition">Batal</a>
                        <button type="submit" class="w-full md:w-auto px-5 py-3 md:py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Simpan Jadwal</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>