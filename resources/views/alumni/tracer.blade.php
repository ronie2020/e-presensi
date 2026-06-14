@extends('layouts.public')

@section('content')
    <div class="min-h-screen bg-slate-50 font-sans text-slate-800 pb-20">
        
        {{-- HEADER --}}
        <div class="bg-slate-900 pt-20 pb-32 rounded-b-[3rem] shadow-xl relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            
            {{-- Decoration --}}
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none translate-x-1/2 -translate-y-1/2"></div>

            <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
                <a href="{{ route('alumni.dashboard') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white mb-6 text-sm font-bold transition-colors">
                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                </a>
                <h1 class="text-3xl md:text-4xl font-black text-white mb-2 tracking-tight">Tracer Study</h1>
                <p class="text-slate-400 max-w-xl mx-auto">Dimana kamu melanjutkan sekolah saat ini? Bantu sekolah mendata sebaran alumni dengan mengisi form ini.</p>
            </div>
        </div>

        {{-- FORM CONTAINER --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 -mt-20 relative z-20">
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                
                @if ($errors->any())
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 m-8 mb-0 rounded-r-2xl">
                    <div class="flex items-center gap-2 text-rose-700 font-bold mb-1">
                        <i class="ph-bold ph-warning-circle"></i> Terdapat kesalahan pengisian:
                    </div>
                    <ul class="list-disc list-inside text-xs text-rose-600 ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @php
                    // Logika Konversi Data Lama: Bekerja/Mencari Kerja dialihkan ke Tidak Lanjut
                    $currentStatus = old('activity_status', $profile?->activity_status ?? 'SMA');
                    if(in_array($currentStatus, ['Bekerja', 'Lainnya', 'Mencari Kerja'])) {
                        $currentStatus = 'Tidak Lanjut';
                    }
                @endphp

                <form action="{{ route('alumni.store_tracer') }}" method="POST" 
                      x-data="{ 
                          status: '{{ $currentStatus }}',
                          rating: {{ old('rating', $profile?->rating ?? 5) }}
                      }">
                    @csrf

                    <div class="p-8 md:p-12 space-y-10">
                        
                        {{-- 1. KONTAK --}}
                        <div>
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-sm"><i class="ph-duotone ph-address-book"></i></div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-800">Kontak Terkini</h3>
                                    <p class="text-sm text-slate-500">Agar sekolah tetap bisa menghubungimu.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nomor WhatsApp (Aktif) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number', $profile?->phone_number ?? $student->phone) }}" required 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 h-12 px-4 transition-all @error('phone_number') border-rose-300 bg-rose-50 @enderror"
                                           placeholder="Contoh: 08123456789">
                                    @error('phone_number')<span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Email Pribadi <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $profile?->email ?? '') }}" required 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 h-12 px-4 transition-all @error('email') border-rose-300 bg-rose-50 @enderror"
                                           placeholder="nama@email.com">
                                    @error('email')<span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        {{-- 2. STATUS AKTIVITAS --}}
                        <div>
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl shadow-sm"><i class="ph-duotone ph-backpack"></i></div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-800">Aktivitas Saat Ini</h3>
                                    <p class="text-sm text-slate-500">Pilih kegiatan utamamu setelah lulus dari SMP.</p>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 ml-1">Status Aktivitas <span class="text-rose-500">*</span></label>
                                
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                                    {{-- Opsi SMA --}}
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="activity_status" value="SMA" x-model="status" class="peer sr-only">
                                        <div class="px-2 py-4 rounded-2xl border-2 border-slate-100 text-center font-bold text-slate-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-lg peer-checked:shadow-blue-500/30 transition-all hover:border-blue-200 hover:bg-slate-50">
                                            <i class="ph-bold ph-student text-2xl mb-1 block peer-checked:text-white text-slate-400 group-hover:text-blue-500"></i><span class="text-xs lg:text-sm">SMA</span>
                                        </div>
                                    </label>
                                    {{-- Opsi SMK --}}
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="activity_status" value="SMK" x-model="status" class="peer sr-only">
                                        <div class="px-2 py-4 rounded-2xl border-2 border-slate-100 text-center font-bold text-slate-600 peer-checked:bg-orange-600 peer-checked:text-white peer-checked:border-orange-600 peer-checked:shadow-lg peer-checked:shadow-orange-500/30 transition-all hover:border-orange-200 hover:bg-slate-50">
                                            <i class="ph-bold ph-wrench text-2xl mb-1 block peer-checked:text-white text-slate-400 group-hover:text-orange-500"></i><span class="text-xs lg:text-sm">SMK</span>
                                        </div>
                                    </label>
                                    {{-- Opsi MA --}}
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="activity_status" value="MA" x-model="status" class="peer sr-only">
                                        <div class="px-2 py-4 rounded-2xl border-2 border-slate-100 text-center font-bold text-slate-600 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 peer-checked:shadow-lg peer-checked:shadow-emerald-500/30 transition-all hover:border-emerald-200 hover:bg-slate-50">
                                            <i class="ph-bold ph-book-open-text text-2xl mb-1 block peer-checked:text-white text-slate-400 group-hover:text-emerald-500"></i><span class="text-xs lg:text-sm">MA</span>
                                        </div>
                                    </label>
                                    {{-- Opsi Pesantren --}}
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="activity_status" value="Pesantren" x-model="status" class="peer sr-only">
                                        <div class="px-2 py-4 rounded-2xl border-2 border-slate-100 text-center font-bold text-slate-600 peer-checked:bg-teal-600 peer-checked:text-white peer-checked:border-teal-600 peer-checked:shadow-lg peer-checked:shadow-teal-500/30 transition-all hover:border-teal-200 hover:bg-slate-50">
                                            <i class="ph-bold ph-mosque text-2xl mb-1 block peer-checked:text-white text-slate-400 group-hover:text-teal-500"></i><span class="text-[11px] lg:text-sm">Pesantren</span>
                                        </div>
                                    </label>
                                    {{-- Opsi Paket C --}}
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="activity_status" value="Paket C" x-model="status" class="peer sr-only">
                                        <div class="px-2 py-4 rounded-2xl border-2 border-slate-100 text-center font-bold text-slate-600 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 peer-checked:shadow-lg peer-checked:shadow-indigo-500/30 transition-all hover:border-indigo-300 hover:bg-slate-50">
                                            <i class="ph-bold ph-certificate text-2xl mb-1 block peer-checked:text-white text-slate-400 group-hover:text-indigo-600"></i><span class="text-xs lg:text-sm">Paket C</span>
                                        </div>
                                    </label>
                                    {{-- Opsi Tidak Lanjut --}}
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="activity_status" value="Tidak Lanjut" x-model="status" class="peer sr-only">
                                        <div class="px-2 py-4 rounded-2xl border-2 border-slate-100 text-center font-bold text-slate-600 peer-checked:bg-slate-700 peer-checked:text-white peer-checked:border-slate-700 peer-checked:shadow-lg peer-checked:shadow-slate-500/30 transition-all hover:border-slate-300 hover:bg-slate-50" title="Belum Melanjutkan Pendidikan">
                                            <i class="ph-bold ph-house text-2xl mb-1 block peer-checked:text-white text-slate-400 group-hover:text-slate-600"></i><span class="text-[11px] lg:text-sm">Tidak Lanjut</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- KONTEN DINAMIS: SEKOLAH LANJUTAN --}}
                            <div x-show="['SMA', 'SMK', 'MA', 'Pesantren', 'Paket C'].includes(status)" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="bg-blue-50/50 p-6 rounded-3xl border border-blue-100 mb-4" x-cloak>
                                
                                <h4 class="text-sm font-bold text-blue-800 mb-4 flex items-center gap-2"><i class="ph-fill ph-buildings"></i> Detail Sekolah Lanjutan</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="col-span-2 md:col-span-2">
                                        <label class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1 block">Nama Sekolah / Pesantren / PKBM <span class="text-rose-500">*</span></label>
                                        <input type="text" name="campus_name" value="{{ old('campus_name', $profile?->campus_name ?? '') }}" placeholder="Contoh: SMAN 1 Lakbok / SMK Taruna"
                                               class="w-full rounded-xl border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 text-sm font-bold h-12 bg-white transition-all px-4"
                                               x-bind:required="['SMA', 'SMK', 'MA', 'Pesantren', 'Paket C'].includes(status)">
                                    </div>
                                    
                                    <div x-show="status !== 'Pesantren'">
                                        <label class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1 block">Jurusan / Peminatan</label>
                                        <input type="text" name="campus_major" value="{{ old('campus_major', $profile?->campus_major ?? '') }}" placeholder="Contoh: TKJ, IPA, Otomotif"
                                               class="w-full rounded-xl border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 text-sm font-bold h-12 bg-white transition-all px-4">
                                    </div>

                                    <div>
                                        <label class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1 block">Tahun Masuk <span class="text-rose-500">*</span></label>
                                        <input type="number" name="campus_entry_year" value="{{ old('campus_entry_year', $profile?->campus_entry_year ?? date('Y')) }}"
                                               min="2000" max="{{ date('Y') + 1 }}"
                                               class="w-full rounded-xl border-blue-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 text-sm font-bold h-12 bg-white transition-all px-4"
                                               x-bind:required="['SMA', 'SMK', 'MA', 'Pesantren', 'Paket C'].includes(status)">
                                    </div>
                                </div>
                            </div>

                            {{-- KONTEN DINAMIS: TIDAK LANJUT --}}
                            <div x-show="status === 'Tidak Lanjut'" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="bg-slate-50 p-6 rounded-3xl border border-slate-200 mb-4" x-cloak>
                                
                                <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2"><i class="ph-fill ph-info"></i> Detail Kegiatan / Pekerjaan</h4>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="col-span-1 mb-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 block">
                                            Keterangan Kegiatan / Kesibukan <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="position" value="{{ old('position', $profile?->position ?? '') }}" 
                                               placeholder="Contoh: Membantu Orang Tua / Bekerja / Kursus"
                                               class="w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-4 focus:ring-slate-100 text-sm font-bold h-12 bg-white transition-all px-4" 
                                               x-bind:required="status === 'Tidak Lanjut'">
                                    </div>

                                    <!-- UI Enhancement: Information Cards -->
                                    <div class="col-span-1 mt-2">
                                        <div class="bg-slate-100/50 border border-slate-200 rounded-2xl p-4 flex gap-3 items-start shadow-sm">
                                            <i class="ph-fill ph-house text-slate-400 text-xl"></i>
                                            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                                <strong>Tidak Melanjutkan:</strong> Tidak masalah jika saat ini Anda belum melanjutkan pendidikan formal. Silakan tuliskan kegiatan positif Anda pada kolom di atas (misal: "Bekerja mandiri", "Membantu orang tua", "Kursus", dsb).
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        {{-- 3. TESTIMONI --}}
                        <div>
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl shadow-sm"><i class="ph-duotone ph-heart"></i></div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-800">Kesan & Pesan</h3>
                                    <p class="text-sm text-slate-500">Bagikan pengalamanmu selama bersekolah.</p>
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Rating Sekolah</label>
                                <div class="flex gap-2 p-4 bg-slate-50 rounded-2xl border border-slate-100 w-fit">
                                    <template x-for="i in 5">
                                        <button type="button" @click="rating = i" class="text-3xl transition-transform hover:scale-110 focus:outline-none">
                                            <i class="ph-fill ph-star" :class="i <= rating ? 'text-amber-400 drop-shadow-sm' : 'text-slate-200'"></i>
                                        </button>
                                    </template>
                                    <input type="hidden" name="rating" x-model="rating">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Testimoni / Pesan (Opsional)</label>
                                <textarea name="testimony" rows="4" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-rose-100 focus:border-rose-500 p-4 font-medium transition-all" placeholder="Ceritakan kenangan terbaikmu di SMPN 3 Lakbok...">{{ old('testimony', $profile?->testimony ?? '') }}</textarea>
                            </div>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-xs text-slate-400 text-center md:text-left">Pastikan data yang Anda masukkan adalah benar dan valid.</p>
                        <button type="submit" class="px-8 py-3 bg-slate-900 text-white font-bold rounded-xl shadow-lg shadow-slate-900/20 hover:bg-slate-800 hover:-translate-y-0.5 transition transform flex items-center gap-2 w-full md:w-auto justify-center">
                            <i class="ph-bold ph-floppy-disk"></i> Simpan Data Alumni
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection