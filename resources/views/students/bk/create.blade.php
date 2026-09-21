@extends('layouts.public')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--multiple {
            background-color: rgba(2, 11, 24, 0.9) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            border-radius: 1rem !important;
            padding: 0.5rem !important;
            min-height: 52px !important;
        }
        .select2-container--focus .select2-selection--multiple {
            background-color: rgba(2, 11, 24, 0.95) !important;
            border-color: #56bbf1 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: rgba(86, 187, 241, 0.15) !important;
            border: 1px solid rgba(86, 187, 241, 0.3) !important;
            border-radius: 0.5rem !important;
            padding: 4px 8px !important;
            color: #56bbf1 !important;
            font-weight: bold !important;
            font-size: 0.875rem !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #f43f5e !important;
            margin-right: 6px !important;
        }
        .select2-dropdown {
            background-color: #021124 !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #fff !important;
        }
        .select2-results__option {
            color: #e2e8f0 !important;
        }
        .select2-results__option--highlighted[aria-selected] {
            background-color: rgba(86, 187, 241, 0.2) !important;
            color: #56bbf1 !important;
        }
    </style>
@endpush

@section('content')
<div class="min-h-screen bg-[#020b18] font-sans text-slate-100 pb-20 pt-24">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6">
        
        <!-- Tombol Kembali -->
        <a href="{{ route('student.bk.index') }}" class="group inline-flex items-center text-sm text-[#56bbf1] hover:text-white font-bold mb-6 transition-colors">
            <i class="ph-bold ph-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Riwayat
        </a>

        <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl rounded-[2rem] shadow-2xl border border-white/10 overflow-hidden relative">
            <!-- Header Dekorasi -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#56bbf1] to-[#3b82f6]"></div>
            
            <div class="p-6 md:p-10">
                <div class="flex items-center gap-5 mb-8">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 text-[#56bbf1] flex items-center justify-center text-3xl shadow-sm border border-white/10">
                        <i class="ph-fill ph-heartbeat animate-pulse"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white">Mulai Konsultasi</h2>
                        <p class="text-slate-300 text-sm font-medium">Ceritakan masalahmu, privasi dijamin aman 100%.</p>
                    </div>
                </div>
                
                <form action="{{ route('student.bk.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- 1. Kategori Masalah -->
                    <div>
                        <label class="block text-sm font-bold text-slate-300 uppercase tracking-wider mb-3">
                            Pilih Topik Permasalahan <span class="text-rose-400">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($categories as $cat)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="bk_category_id" value="{{ $cat->id }}" class="peer sr-only" required>
                                
                                <div class="relative overflow-hidden rounded-2xl border-2 border-white/10 bg-[#021124]/80 p-4 transition-all duration-200 ease-in-out
                                            hover:border-[#56bbf1]/50 hover:bg-white/5 
                                            peer-checked:border-[#56bbf1] peer-checked:bg-[#56bbf1]/15 peer-checked:shadow-md peer-checked:scale-[1.02]">
                                    
                                    <div class="flex items-center gap-3 relative z-10">
                                        <div class="w-6 h-6 rounded-full border-2 border-white/20 bg-[#021124] flex items-center justify-center transition-all duration-300 peer-checked:border-[#56bbf1]">
                                            <div class="w-3 h-3 rounded-full bg-[#56bbf1] opacity-0 transform scale-0 transition-all duration-300 peer-checked:opacity-100 peer-checked:scale-100"></div>
                                        </div>
                                        
                                        <span class="text-sm font-bold text-slate-300 group-hover:text-[#56bbf1] peer-checked:text-white transition-colors">
                                            {{ $cat->name }}
                                        </span>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('bk_category_id') 
                            <p class="text-rose-400 text-xs mt-2 font-bold flex items-center gap-1">
                                <i class="ph-bold ph-warning"></i> {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- 2. Pesan Awal -->
                    <div>
                        <label for="initial_message" class="block text-sm font-bold text-slate-300 uppercase tracking-wider mb-3">
                            Apa yang sedang kamu rasakan? <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <textarea name="initial_message" id="initial_message" rows="6" 
                                class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#021124] shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] p-5 text-white leading-relaxed resize-none transition-all placeholder:text-slate-500" 
                                placeholder="Contoh: Saya merasa kesulitan membagi waktu antara belajar dan ekskul, nilai saya jadi turun..." required>{{ old('initial_message') }}</textarea>
                            
                            <div class="absolute top-4 right-4 p-2 bg-white/10 rounded-lg text-slate-400 border border-white/10">
                                <i class="ph-fill ph-pencil-simple text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2 text-right font-medium">Minimal 10 karakter</p>
                        @error('initial_message') 
                            <p class="text-rose-400 text-xs mt-2 font-bold flex items-center gap-1">
                                <i class="ph-bold ph-warning"></i> {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- 3. Teman Kelompok (Opsional) -->
                    <div>
                        <label for="friends" class="block text-sm font-bold text-slate-300 uppercase tracking-wider mb-3">
                            Konseling Kelompok (Opsional)
                        </label>
                        <div class="relative">
                            <select name="friends[]" id="friends" multiple class="w-full rounded-2xl border-white/15 bg-[#021124]/90 p-3 text-white transition-all select2-hidden-accessible" style="width: 100%;">
                                @foreach($classmates as $mate)
                                    <option value="{{ $mate->id }}" {{ (is_array(old('friends')) && in_array($mate->id, old('friends'))) ? 'selected' : '' }}>{{ $mate->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-xs text-slate-400 mt-2 font-medium">Pilih teman sekelas jika kamu ingin melakukan konseling kelompok (bersama-sama).</p>
                        @error('friends') 
                            <p class="text-rose-400 text-xs mt-2 font-bold flex items-center gap-1">
                                <i class="ph-bold ph-warning"></i> {{ $message }}
                            </p> 
                        @error
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-300 uppercase tracking-wider mb-3">
                            Metode Konseling <span class="text-rose-400">*</span>
                        </label>
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Pilihan Offline -->
                            <label class="flex-1 relative cursor-pointer group">
                                <input type="radio" name="method" value="offline" class="peer sr-only" checked>
                                <div class="p-5 rounded-2xl border-2 border-white/10 bg-[#021124]/80 transition-all duration-200
                                            hover:bg-white/5 hover:border-[#56bbf1]/50
                                            peer-checked:border-[#56bbf1] peer-checked:bg-[#56bbf1]/15 peer-checked:shadow-md flex items-start gap-4">
                                    <div class="p-3 bg-white/10 rounded-xl border border-white/10 text-[#56bbf1] text-2xl shadow-sm transition-colors">
                                        <i class="ph-fill ph-users-three"></i>
                                    </div>
                                    <div>
                                        <span class="block font-black text-white mb-1 transition-colors">Tatap Muka</span>
                                        <span class="text-xs text-slate-300 font-medium leading-tight block">Bertemu langsung dengan Guru BK di ruangan.</span>
                                    </div>
                                    
                                    <div class="ml-auto w-6 h-6 rounded-full border-2 border-white/20 bg-[#021124] flex items-center justify-center transition-all duration-300 peer-checked:border-[#56bbf1]">
                                        <div class="w-3 h-3 rounded-full bg-[#56bbf1] opacity-0 transform scale-0 transition-all duration-300 peer-checked:opacity-100 peer-checked:scale-100"></div>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Pilihan Online -->
                            <label class="flex-1 relative cursor-pointer group">
                                <input type="radio" name="method" value="online" class="peer sr-only">
                                <div class="p-5 rounded-2xl border-2 border-white/10 bg-[#021124]/80 transition-all duration-200
                                            hover:bg-white/5 hover:border-[#56bbf1]/50
                                            peer-checked:border-[#56bbf1] peer-checked:bg-[#56bbf1]/15 peer-checked:shadow-md flex items-start gap-4">
                                    <div class="p-3 bg-white/10 rounded-xl border border-white/10 text-[#56bbf1] text-2xl shadow-sm transition-colors">
                                        <i class="ph-fill ph-chat-circle-text"></i>
                                    </div>
                                    <div>
                                        <span class="block font-black text-white mb-1 transition-colors">Online (Chat/WA)</span>
                                        <span class="text-xs text-slate-300 font-medium leading-tight block">Konseling jarak jauh melalui media komunikasi.</span>
                                    </div>

                                    <div class="ml-auto w-6 h-6 rounded-full border-2 border-white/20 bg-[#021124] flex items-center justify-center transition-all duration-300 peer-checked:border-[#56bbf1]">
                                        <div class="w-3 h-3 rounded-full bg-[#56bbf1] opacity-0 transform scale-0 transition-all duration-300 peer-checked:opacity-100 peer-checked:scale-100"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Footer & Button -->
                    <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-3 text-slate-300 text-xs bg-white/5 px-4 py-3 rounded-xl border border-white/10">
                            <i class="ph-fill ph-lock-key text-[#56bbf1] text-lg"></i>
                            <span>Data ini bersifat <strong>RAHASIA</strong> & hanya diketahui Guru BK.</span>
                        </div>
                        
                        <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-8 py-4 border border-transparent font-bold rounded-xl text-white bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] hover:brightness-110 shadow-xl shadow-[#56bbf1]/20 hover:-translate-y-1 transition-all duration-300">
                            <span>Kirim Pengajuan</span>
                            <i class="ph-bold ph-paper-plane-right ml-2 text-lg"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#friends').select2({
            placeholder: "Pilih teman...",
            allowClear: true,
            width: 'resolve'
        });
    });
</script>
@endpush