@extends('layouts.public')

@section('content')
    <div class="w-full max-w-md mx-auto">

        <!-- Header Teks (Opsional, agar tidak terlalu sepi) -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang Siswa</h2>
            <p class="text-gray-500 text-sm mt-1">Silakan cek data akademik Anda di sini</p>
        </div>

        <!-- Form Pencarian -->
        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
            
            <form action="{{ route('portal.search') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Induk Siswa (NIS / NISN)
                    </label>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i class="ph-bold ph-identification-card text-lg"></i>
                        </div>
                        <input type="text" name="student_id" id="student_id" 
                            class="pl-10 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                            placeholder="Contoh: 12345678"
                            value="{{ old('student_id') }}"
                            required autofocus>
                    </div>

                    <!-- Error Message -->
                    @error('student_id')
                        <p class="text-sm text-red-600 mt-2 flex items-center">
                            <i class="ph-fill ph-warning-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                    
                    @if (session('error'))
                        <div class="mt-3 bg-red-50 border border-red-100 text-red-600 px-3 py-2 rounded-lg text-sm flex items-center">
                            <i class="ph-fill ph-warning mr-2"></i> {{ session('error') }}
                        </div>
                    @endif
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200 flex justify-center items-center gap-2 shadow-lg shadow-blue-500/30">
                    <i class="ph-bold ph-magnifying-glass"></i>
                    Cari Data Siswa
                </button>
            </form>

            <!-- PEMISAH (Divider) -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Atau</span>
                </div>
            </div>

            <!-- TOMBOL KEMBALI KE HALAMAN UTAMA -->
            <div class="text-center">
                <a href="{{ route('landing') }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="ph-bold ph-arrow-left mr-2"></i>
                    Kembali ke Halaman Utama
                </a>
            </div>

        </div>
        
        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. Hak Cipta Dilindungi.
        </p>

    </div>
@endsection