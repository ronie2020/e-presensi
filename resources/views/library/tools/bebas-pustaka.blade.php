<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-r from-sky-600/20 via-blue-600/10 to-transparent pointer-events-none -z-10 blur-3xl"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <a href="{{ route('library.tools.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-sky-400 mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Alat
            </a>

            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative backdrop-blur-xl">
                
                <div class="bg-gradient-to-r from-sky-900/40 via-blue-900/20 to-slate-900/40 p-8 text-white relative overflow-hidden border-b border-white/10">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-certificate"></i>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-sky-500/20 text-sky-300 rounded-lg text-[10px] font-black uppercase tracking-widest border border-sky-500/30 backdrop-blur-md shadow-sm">
                            Administrasi Kelulusan
                        </span>
                    </div>
                    <h2 class="text-3xl font-black relative z-10 tracking-tight text-white">Cek Bebas Pustaka</h2>
                    <p class="text-slate-300 text-sm font-semibold relative z-10 mt-2 max-w-xl leading-relaxed">
                        Periksa status pinjaman siswa sebelum menerbitkan Surat Keterangan Bebas Perpustakaan.
                    </p>
                </div>

                <div class="p-8">
                    <div class="bg-slate-900/60 p-6 rounded-[2rem] border border-white/10 mb-8">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-magnifying-glass text-sky-400 text-lg"></i> Cari Data Siswa
                        </h3>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="relative flex-1 group">
                                <i class="ph-bold ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xl group-focus-within:text-sky-400 transition-colors"></i>
                                <input type="text" id="searchInput" placeholder="Masukkan NISN, NIS, atau Nama Siswa..." 
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl border border-white/10 bg-slate-900/80 text-white placeholder-slate-500 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold transition-all shadow-sm text-lg [color-scheme:dark]">
                            </div>
                            <button type="button" onclick="checkClearance()" id="btnCheck" class="px-8 py-4 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold rounded-2xl shadow-lg shadow-sky-500/20 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                                <i class="ph-bold ph-check-circle text-xl"></i> Cek Status
                            </button>
                        </div>
                    </div>

                    {{-- Loading Indicator --}}
                    <div id="loadingArea" class="hidden py-10 text-center">
                        <i class="ph-bold ph-spinner animate-spin text-4xl text-sky-400 mx-auto mb-3"></i>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Memeriksa Database...</p>
                    </div>

                    {{-- Area Hasil --}}
                    <div id="resultArea" class="hidden animate-fade-in-down"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); checkClearance(); }
        });

        async function checkClearance() {
            const query = document.getElementById('searchInput').value.trim();
            if (!query) return;

            const btnCheck = document.getElementById('btnCheck');
            const loadingArea = document.getElementById('loadingArea');
            const resultArea = document.getElementById('resultArea');

            btnCheck.disabled = true;
            resultArea.classList.add('hidden');
            loadingArea.classList.remove('hidden');

            try {
                // Memanggil API
                const response = await fetch(`{{ route('library.tools.checkClearanceApi') }}?q=${encodeURIComponent(query)}`);
                const data = await response.json();

                loadingArea.classList.add('hidden');
                resultArea.classList.remove('hidden');

                if (data.success) { renderResult(data); } 
                else { renderNotFound(data.message); }

            } catch (error) {
                loadingArea.classList.add('hidden');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal menghubungi server.',
                    background: '#021124',
                    color: '#fff',
                    confirmButtonColor: '#0d52a1',
                    customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' }
                });
            } finally {
                btnCheck.disabled = false;
            }
        }

        function renderResult(data) {
            const resultArea = document.getElementById('resultArea');
            const student = data.student;
            const hasLoans = data.active_loans.length > 0;
            
            const printUrl = `{{ url('library/tools/print-clearance') }}/${student.id}`;

            let statusHtml = '';
            let actionHtml = '';

            if (hasLoans) {
                let booksList = '';
                data.active_loans.forEach(loan => {
                    booksList += `
                        <li class="py-2.5 border-b border-rose-500/20 last:border-0 flex justify-between items-center">
                            <div>
                                <span class="font-bold text-sm text-rose-200 block">${loan.book_title}</span>
                                <span class="text-[10px] text-rose-300 font-mono bg-rose-900/50 border border-rose-500/30 px-2 py-0.5 rounded mt-1 inline-block">Kode Fisik: ${loan.item_code || 'Reguler'}</span>
                            </div>
                        </li>`;
                });

                statusHtml = `
                    <div class="bg-rose-950/40 border border-rose-500/30 rounded-[2rem] p-6 relative overflow-hidden backdrop-blur-md">
                        <div class="flex items-start gap-4 relative z-10">
                            <div class="w-12 h-12 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 border border-rose-500/30">
                                <i class="ph-bold ph-x text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-black text-rose-300">Tanggungan Ditemukan!</h4>
                                <p class="text-sm text-rose-200/80 mb-3">Siswa ini belum bisa mendapatkan Surat Bebas Pustaka karena belum mengembalikan:</p>
                                <ul class="bg-slate-900/90 rounded-xl p-3 border border-rose-500/20 text-slate-200">${booksList}</ul>
                            </div>
                        </div>
                    </div>`;
                    
                actionHtml = `<button disabled class="w-full py-4 mt-6 bg-slate-800/60 text-slate-500 font-bold rounded-2xl cursor-not-allowed border border-white/5">Buku Belum Dikembalikan - Tidak Dapat Mencetak</button>`;
            } else {
                statusHtml = `
                    <div class="bg-emerald-950/40 border border-emerald-500/30 rounded-[2rem] p-6 relative overflow-hidden backdrop-blur-md">
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-12 h-12 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                                <i class="ph-bold ph-check text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-emerald-300">Status Bersih</h4>
                                <p class="text-sm text-emerald-200/80">Siswa tidak memiliki tanggungan pinjaman perpustakaan.</p>
                            </div>
                        </div>
                    </div>`;

                actionHtml = `<a href="${printUrl}" target="_blank" class="w-full py-4 mt-6 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-black rounded-2xl shadow-xl shadow-emerald-500/20 transition-all flex items-center justify-center gap-2 transform active:scale-95">
                    <i class="ph-bold ph-printer text-xl"></i> Cetak Surat Bebas Pustaka
                </a>`;
            }

            resultArea.innerHTML = `
                <div class="border border-white/10 rounded-[2rem] p-6 mb-6 flex flex-col md:flex-row gap-6 items-center md:items-start bg-slate-900/80 shadow-md">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-sky-500 to-blue-600 text-white flex items-center justify-center font-black text-3xl shrink-0 border border-white/20 shadow-md">
                        ${student.name.charAt(0)}
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-2xl font-black text-white">${student.name}</h3>
                        <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-2">
                            <span class="px-3 py-1 bg-slate-800 text-slate-300 rounded-lg text-xs font-bold border border-white/10 font-mono">NISN: ${student.nisn || student.student_id || '-'}</span>
                            <span class="px-3 py-1 bg-slate-800 text-slate-300 rounded-lg text-xs font-bold border border-white/10">Kelas: ${student.school_class ? student.school_class.name : '-'}</span>
                        </div>
                    </div>
                </div>
                ${statusHtml}
                ${actionHtml}
            `;
        }

        function renderNotFound(message) {
            document.getElementById('resultArea').innerHTML = `
                <div class="p-8 text-center bg-slate-900/60 border border-white/10 rounded-[2rem]">
                    <div class="w-16 h-16 bg-slate-800/80 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400 border border-white/10">
                        <i class="ph-duotone ph-ghost text-4xl"></i>
                    </div>
                    <h4 class="text-lg font-black text-white">Data Tidak Ditemukan</h4>
                    <p class="text-slate-400 text-sm mt-1">${message}</p>
                </div>`;
        }
    </script>
</x-app-layout>