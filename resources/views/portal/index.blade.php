@extends('layouts.public')

@section('content')
    <div class="max-w-2xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Portal Siswa</h1>
            <p class="text-gray-600 mt-2">Cek Rekap Kehadiran & Poin Disiplin Anda</p>
        </div>

        <!-- Form Pencarian -->
        <div class="bg-white p-8 rounded-xl shadow-lg">
            
            <!-- Arahkan form ke rute 'portal.search' dengan metode POST -->
            <form action="{{ route('portal.search') }}" method="POST">
                
                <!-- INI PENTING: Token Keamanan untuk memperbaiki error '419 Page Expired' -->
                @csrf
                
                <label for="student_id" class="block text-sm font-medium text-gray-700">
                    NIS / NISN
                </label>
                <input type="text" name="student_id" id="student_id" 
                       class="mt-1 block w-full px-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Masukkan NIS atau NISN Anda..."
                       value="{{ old('student_id') }}"
                       required>

                <!-- Menampilkan error validasi (jika NISN kosong) -->
                @error('student_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                
                <!-- Menampilkan error dari Controller (jika Siswa tidak ditemukan) -->
                @if (session('error'))
                    <p class="text-sm text-red-600 mt-1">{{ session('error') }}</p>
                @endif

                <button type="submit" 
                        class="w-full mt-6 bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200">
                    Cari Data Siswa
                </button>
            </form>
        </div>

    </div>
@endsection