<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-2xl font-black text-gray-800 mb-6">Input Nilai & E-Rapor</h1>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <form action="{{ route('grades.create') }}" method="GET">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Pilih Kelas -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Kelas</label>
                            <select name="class_id" class="w-full rounded-xl border-gray-300 focus:ring-violet-500" required>
                                <option value="">-- Pilih --</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilih Mata Pelajaran -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Mata Pelajaran</label>
                            <select name="subject_id" class="w-full rounded-xl border-gray-300 focus:ring-violet-500" required>
                                <option value="">-- Pilih --</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tahun Ajaran (DINAMIS) -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tahun Ajaran</label>
                            <select name="academic_year" class="w-full rounded-xl border-gray-300 focus:ring-violet-500">
                                @foreach($years as $y)
                                    <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>
                                        {{ $y->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Semester (DINAMIS) -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Semester</label>
                            <select name="semester" class="w-full rounded-xl border-gray-300 focus:ring-violet-500">
                                <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil (1)</option>
                                <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap (2)</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 transition shadow-lg">
                        Mulai Input Nilai
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>