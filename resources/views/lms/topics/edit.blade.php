<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('lms.topics.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-elevate-dark hover:bg-slate-50 hover:scale-105 transition-all shadow-sm">
                <i class="ph-bold ph-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
                {{ __('Edit Pokok Bahasan (Bab)') }}
            </h2>
        </div>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        {{-- Efek Latar Belakang --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            @if ($errors->any())
                <div class="mb-6 bg-[#FDE7E9] border border-[#F4C3C9] p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm animate-enter">
                    <div class="p-2 bg-white text-[#D13438] rounded-xl shrink-0"><i class="ph-bold ph-warning text-xl"></i></div>
                    <div>
                        <ul class="list-disc list-inside text-sm text-[#D13438] font-bold mt-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="animate-enter bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-xl shadow-slate-200/40 border border-slate-100">
                <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-100">
                    <div class="w-12 h-12 rounded-2xl bg-elevate-soft text-elevate-primary flex items-center justify-center text-2xl shadow-sm">
                        <i class="ph-bold ph-pencil-simple"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl text-elevate-dark">Ubah Data Bab</h3>
                        <p class="text-sm text-slate-500 font-medium mt-1">Perbarui informasi bab untuk mengatur urutan materi.</p>
                    </div>
                </div>

                <form id="editForm" action="{{ route('lms.topics.update', $topic->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="subject_id" class="block text-[11px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Mapel <span class="text-[#D13438]">*</span></label>
                            <div class="relative group">
                                <select id="subject_id" name="subject_id" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:border-elevate-accent focus:ring-2 focus:ring-elevate-accent/20 h-14 px-4 appearance-none text-sm cursor-pointer shadow-sm transition-all duration-300">
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ (old('subject_id') ?? $topic->subject_id) == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500 group-hover:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        </div>

                        <div>
                            <label for="order_number" class="block text-[11px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Urutan Ke- <span class="text-[#D13438]">*</span></label>
                            <input id="order_number" type="number" name="order_number" value="{{ old('order_number') ?? $topic->order_number }}" min="1" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark text-sm focus:bg-white focus:border-elevate-accent focus:ring-2 focus:ring-elevate-accent/20 h-14 px-4 shadow-sm transition-all duration-300">
                        </div>
                    </div>
                    
                    <div>
                        <label for="title" class="block text-[11px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Judul Bab <span class="text-[#D13438]">*</span></label>
                        <input id="title" type="text" name="title" value="{{ old('title') ?? $topic->title }}" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark text-sm focus:bg-white focus:border-elevate-accent focus:ring-2 focus:ring-elevate-accent/20 h-14 px-4 shadow-sm transition-all duration-300" placeholder="Cth: Bab 1: Sistem Tata Surya">
                    </div>

                    <div>
                        <label for="description" class="block text-[11px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Deskripsi Singkat (Opsional)</label>
                        <textarea id="description" name="description" rows="4" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-medium text-sm focus:bg-white focus:border-elevate-accent focus:ring-2 focus:ring-elevate-accent/20 p-4 shadow-sm placeholder:text-slate-400 transition-all duration-300" placeholder="Apa yang dipelajari di bab ini?">{{ old('description') ?? $topic->description }}</textarea>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('lms.topics.index') }}" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl transition-colors text-sm">
                            Batal
                        </a>
                        <button type="submit" id="submitBtn" class="px-8 py-3.5 bg-elevate-primary text-white font-bold rounded-2xl shadow-lg shadow-elevate-primary/30 hover:bg-elevate-dark transition-all flex items-center justify-center gap-2 text-sm active:scale-95 group">
                            <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Efek loading saat form disubmit
        document.getElementById('editForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin text-lg"></i> <span>Menyimpan...</span>';
            btn.classList.add('opacity-80', 'cursor-not-allowed', 'pointer-events-none');
        });
    </script>
    @endpush
</x-app-layout>