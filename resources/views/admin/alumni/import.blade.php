<x-app-layout>
    <div class="py-8 font-sans text-slate-100 bg-[#020b18] min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER --}}
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('admin.alumni.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#021124]/80 border border-white/15 text-slate-300 hover:text-white hover:border-[#56bbf1]/50 hover:bg-[#031d3d] transition-all shadow-sm">
                    <i class="ph-bold ph-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-white">Import Data Alumni</h1>
                    <p class="text-slate-400 text-sm">Upload data alumni lama (legacy) secara massal.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 rounded-2xl flex items-center gap-3">
                    <i class="ph-fill ph-check-circle text-xl text-emerald-400"></i>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-300 rounded-2xl flex items-center gap-3">
                    <i class="ph-fill ph-warning-circle text-xl text-rose-400"></i>
                    <span class="font-bold text-sm">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-300 rounded-2xl">
                    <ul class="list-disc list-inside font-bold text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- CARD 1: FORM UPLOAD --}}
                <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] p-8 border border-white/10 shadow-2xl">
                    <div class="w-16 h-16 bg-[#56bbf1]/10 text-[#56bbf1] rounded-2xl flex items-center justify-center text-3xl mb-6 border border-[#56bbf1]/20">
                        <i class="ph-duotone ph-file-csv"></i>
                    </div>
                    
                    <h3 class="text-lg font-bold text-white mb-2">Upload File CSV</h3>
                    <p class="text-sm text-slate-400 mb-6 leading-relaxed">
                        Pastikan file Anda berformat <strong class="text-white">.CSV</strong>. Sistem akan otomatis membuatkan akun untuk setiap alumni dengan password default NISN.
                    </p>

                    <form action="{{ route('admin.alumni.import.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih File</label>
                            <input type="file" name="file" accept=".csv" required
                                   class="block w-full text-xs text-slate-400
                                          file:mr-4 file:py-3 file:px-4
                                          file:rounded-xl file:border-0
                                          file:text-xs file:font-bold
                                          file:bg-[#56bbf1]/10 file:text-[#56bbf1]
                                          hover:file:bg-[#56bbf1]/20
                                          border border-white/15 bg-[#021124]/90 rounded-2xl cursor-pointer">
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] text-white font-bold rounded-2xl hover:brightness-110 shadow-lg shadow-sky-950/50 transition-all flex items-center justify-center gap-2 transform active:scale-95 text-sm">
                            <i class="ph-bold ph-upload-simple"></i> Mulai Proses Import
                        </button>
                    </form>
                </div>

                {{-- CARD 2: INSTRUKSI & TEMPLATE --}}
                <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] p-8 border border-white/10 shadow-2xl h-fit">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-info text-[#56bbf1]"></i> Petunjuk Format
                    </h3>
                    
                    <ol class="list-decimal list-inside space-y-3 text-sm text-slate-300 mb-8 font-medium">
                        <li>Download template CSV di bawah ini.</li>
                        <li>Jangan ubah urutan kolom di template.</li>
                        <li><strong class="text-white">PENTING:</strong> Pastikan format file saat Save As di Excel adalah <strong class="text-white">CSV (Comma Delimited)</strong>.</li>
                        <li>Jika menggunakan Excel Indonesia, sistem akan otomatis mendeteksi pemisah titik koma (;).</li>
                    </ol>

                    <a href="{{ route('admin.alumni.template') }}" class="w-full py-3.5 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-200 font-bold rounded-2xl shadow-sm transition-all flex items-center justify-center gap-2 group text-sm">
                        <i class="ph-bold ph-download-simple group-hover:text-[#56bbf1]"></i> Download Template CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>