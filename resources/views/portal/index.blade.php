@extends('layouts.public')

@section('content')
    <div class="w-full max-w-md mx-auto transform hover:scale-105 transition-transform duration-500">

        <!-- Header Teks -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 text-blue-600 mb-4 shadow-sm border border-blue-100">
                <i class="ph-duotone ph-student text-4xl"></i>
            </div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Selamat Datang Siswa</h2>
            <p class="text-gray-500 text-sm mt-2 px-6">Silakan masukkan NIS atau NISN Anda untuk melihat profil akademik & kedisiplinan.</p>
        </div>

        <!-- Form Pencarian -->
        <div class="bg-white p-8 rounded-3xl shadow-xl shadow-blue-100/50 border border-white relative overflow-hidden">
            <!-- Dekorasi -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-50"></div>

            <form action="{{ route('portal.search') }}" method="POST" class="relative z-10">
                @csrf
                
                <div class="mb-6">
                    <label for="student_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        Nomor Induk Siswa (NISN)
                    </label>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="ph-bold ph-identification-card text-xl"></i>
                        </div>
                        <input type="text" name="student_id" id="student_id" 
                            class="pl-12 block w-full px-4 py-3.5 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-all font-mono text-lg font-bold text-gray-700 placeholder-gray-300"
                            placeholder="Contoh: 005487..."
                            value="{{ old('student_id') }}"
                            required autofocus>
                    </div>

                    @error('student_id')
                        <p class="text-sm text-red-600 mt-2 flex items-center font-medium">
                            <i class="ph-fill ph-warning-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                    
                    @if (session('error'))
                        <div class="mt-4 bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl text-sm flex items-start">
                            <i class="ph-fill ph-warning mr-2 mt-0.5 text-lg"></i> 
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    @endif
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 text-white font-bold py-3.5 px-6 rounded-xl hover:bg-blue-700 transition duration-200 flex justify-center items-center gap-2 shadow-lg shadow-blue-500/30 group">
                    <i class="ph-bold ph-magnifying-glass group-hover:scale-110 transition-transform"></i>
                    Cari Data Siswa
                </button>
            </form>

            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-100"></div>
                </div>
                <div class="relative flex justify-center text-[10px] uppercase tracking-widest font-bold">
                    <span class="px-3 bg-white text-gray-400">Navigasi</span>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('landing') }}" class="inline-flex items-center justify-center w-full px-4 py-3 border border-gray-200 shadow-sm text-sm font-bold rounded-xl text-gray-600 bg-white hover:bg-gray-50 hover:text-blue-600 hover:border-blue-200 transition-all group">
                    <i class="ph-bold ph-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
@endsection