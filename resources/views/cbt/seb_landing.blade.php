@extends('layouts.public')

@section('content')
    <div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            
            <div class="bg-white py-8 px-4 shadow-xl border border-slate-100 sm:rounded-2xl sm:px-10 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                <div class="mb-4 flex justify-center">
                    <div class="h-16 w-16 bg-blue-50 rounded-full flex items-center justify-center border border-blue-100">
                        <i class="ph-duotone ph-shield-check text-3xl text-blue-600"></i>
                    </div>
                </div>

                <h2 class="text-xl font-black text-slate-800 mb-2">Perangkat Terproteksi</h2>
                <p class="text-slate-500 mb-6 text-sm">
                    Pilih perangkat yang Anda gunakan untuk ujian <strong>{{ $exam->title }}</strong>.
                </p>

                <!-- TAB PILIHAN DEVICE -->
                <div x-data="{ tab: 'hp' }" class="space-y-4">
                    <div class="flex p-1 bg-slate-100 rounded-xl">
                        <button @click="tab = 'hp'" :class="tab === 'hp' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2 text-xs font-bold rounded-lg transition">
                            <i class="ph-bold ph-device-mobile"></i> HP (Android)
                        </button>
                        <button @click="tab = 'laptop'" :class="tab === 'laptop' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-2 text-xs font-bold rounded-lg transition">
                            <i class="ph-bold ph-laptop"></i> Laptop / PC
                        </button>
                    </div>

                    <!-- KONTEN HP -->
                    <div x-show="tab === 'hp'" class="text-left bg-blue-50 p-4 rounded-xl border border-blue-100">
                        <h4 class="font-bold text-sm text-blue-800 mb-2">Pengguna Android</h4>
                        <ol class="list-decimal list-inside text-xs text-blue-800/80 space-y-1 mb-4">
                            <li>Download aplikasi <strong>Ujian Sekolah</strong> (APK).</li>
                            <li>Install dan buka aplikasi tersebut.</li>
                            <li>Login dan masuk ke menu ujian lewat aplikasi.</li>
                        </ol>
                        <a href="#" class="block w-full py-2.5 bg-blue-600 text-white font-bold text-xs rounded-lg text-center hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                            <i class="ph-bold ph-android-logo"></i> Download Aplikasi (.apk)
                        </a>
                        <p class="text-[10px] text-center text-blue-400 mt-2">*Hanya bisa dibuka lewat aplikasi ini</p>
                    </div>

                    <!-- KONTEN LAPTOP (SEB) -->
                    <div x-show="tab === 'laptop'" style="display: none;" class="space-y-3">
                        <div class="text-left bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <p class="text-xs text-slate-500 mb-2 font-bold">1. Install Aplikasi</p>
                            <a href="https://safeexambrowser.org/download_en.html" target="_blank" class="block w-full py-2 bg-white border border-slate-300 text-slate-700 text-xs font-bold rounded-lg text-center">
                                Download SEB
                            </a>
                        </div>
                        <div class="text-left bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <p class="text-xs text-slate-500 mb-2 font-bold">2. Masuk Ujian</p>
                            <a href="{{ route('cbt.download_seb', $exam->id) }}" class="block w-full py-2 bg-slate-800 text-white text-xs font-bold rounded-lg text-center">
                                Download Config (.seb)
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-100 pt-4">
                    <a href="{{ route('student.exam.index') }}" class="text-xs text-slate-400 hover:text-slate-600 font-bold">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endsection