<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <a href="{{ route('library.tools.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-elevate-dark/60 hover:text-elevate-primary mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Alat
            </a>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                <div class="bg-elevate-gradient-main p-8 text-elevate-dark relative overflow-hidden border-b border-slate-100">
                    <div class="absolute -right-6 -top-6 text-white/40 text-9xl pointer-events-none mix-blend-overlay">
                        <i class="ph-fill ph-certificate"></i>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-white/50 rounded-lg text-[10px] font-black uppercase tracking-widest border border-white/60 backdrop-blur-md shadow-sm">
                            Administrasi Kelulusan
                        </span>
                    </div>
                    <h2 class="text-3xl font-black relative z-10 tracking-tight">Cek Bebas Pustaka</h2>
                    <p class="text-elevate-dark/80 text-sm font-semibold relative z-10 mt-2 max-w-xl leading-relaxed">
                        Periksa status pinjaman siswa sebelum menerbitkan Surat Keterangan Bebas Perpustakaan.
                    </p>
                </div>

                <div class="p-8">
                    <div class="bg-elevate-soft p-6 rounded-[2rem] border border-slate-200 mb-8">
                        <h3 class="text-xs font-black text-elevate-dark/60 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-magnifying-glass text-elevate-primary text-lg"></i> Cari Data Siswa
                        </h3>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="relative flex-1 group">
                                <i class="ph-bold ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl group-focus-within:text-elevate-primary transition-colors"></i>
                                <input type="text" id="searchInput" placeholder="Masukkan NISN, NIS, atau Nama Siswa..." 
                                    class="w-full pl-12 pr-4 py-4 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark transition-all shadow-sm text-lg">
                            </div>
                            <button type="button" onclick="checkClearance()" id="btnCheck" class="px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                                <i class="ph-bold ph-check-circle text-xl"></i> Cek Status
                            </button>
                        </div>
                    </div>

                    {{-- Loading Indicator --}}
                    <div id="loadingArea" class="hidden py-10 text-center">
                        <i class="ph-bold ph-spinner animate-spin text-4xl text-elevate-primary mx-auto mb-3"></i>
                        <p class="text-sm font-bold text-elevate-dark/60 uppercase tracking-widest">Memeriksa Database...</p>
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
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server.', customClass: { popup: 'rounded-[2rem]' } });
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
                        <li class="py-2 border-b border-rose-100 last:border-0 flex justify-between items-center">
                            <div>
                                <span class="font-bold text-sm text-rose-900 block">${loan.book_title}</span>
                                <span class="text-[10px] text-rose-500 font-mono bg-rose-100 px-2 py-0.5 rounded mt-1 inline-block">Kode Fisik: ${loan.item_code || 'Reguler'}</span>
                            </div>
                        </li>`;
                });

                statusHtml = `
                    <div class="bg-rose-50 border border-rose-200 rounded-[2rem] p-6 relative overflow-hidden">
                        <div class="flex items-start gap-4 relative z-10">
                            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200">
                                <i class="ph-bold ph-x text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-black text-rose-700">Tanggungan Ditemukan!</h4>
                                <p class="text-sm text-rose-600 mb-3">Siswa ini belum bisa mendapatkan Surat Bebas Pustaka karena belum mengembalikan:</p>
                                <ul class="bg-white/50 rounded-xl p-3 border border-rose-100">${booksList}</ul>
                            </div>
                        </div>
                    </div>`;
                    
                actionHtml = `<button disabled class="w-full py-4 mt-6 bg-slate-100 text-slate-400 font-bold rounded-2xl cursor-not-allowed border border-slate-200">Buku Belum Dikembalikan - Tidak Dapat Mencetak</button>`;
            } else {
                statusHtml = `
                    <div class="bg-emerald-50 border border-emerald-200 rounded-[2rem] p-6 relative overflow-hidden">
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-200">
                                <i class="ph-bold ph-check text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-emerald-700">Status Bersih</h4>
                                <p class="text-sm text-emerald-600">Siswa tidak memiliki tanggungan pinjaman perpustakaan.</p>
                            </div>
                        </div>
                    </div>`;

                actionHtml = `<a href="${printUrl}" target="_blank" class="w-full py-4 mt-6 bg-elevate-dark hover:bg-elevate-primary text-white font-black rounded-2xl shadow-xl shadow-elevate-dark/20 transition-all flex items-center justify-center gap-2 transform active:scale-95">
                    <i class="ph-bold ph-printer text-xl"></i> Cetak Surat Bebas Pustaka
                </a>`;
            }

            resultArea.innerHTML = `
                <div class="border border-slate-200 rounded-[2rem] p-6 mb-6 flex flex-col md:flex-row gap-6 items-center md:items-start bg-elevate-gradient-card shadow-sm">
                    <div class="w-20 h-20 rounded-full bg-white text-elevate-primary flex items-center justify-center font-black text-3xl shrink-0 border border-slate-200 shadow-sm">
                        ${student.name.charAt(0)}
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-2xl font-black text-elevate-dark">${student.name}</h3>
                        <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-2">
                            <span class="px-3 py-1 bg-white text-elevate-dark/70 rounded-lg text-xs font-bold border border-slate-200 font-mono shadow-sm">NISN: ${student.nisn || student.student_id || '-'}</span>
                            <span class="px-3 py-1 bg-white text-elevate-dark/70 rounded-lg text-xs font-bold border border-slate-200 shadow-sm">Kelas: ${student.school_class ? student.school_class.name : '-'}</span>
                        </div>
                    </div>
                </div>
                ${statusHtml}
                ${actionHtml}
            `;
        }

        function renderNotFound(message) {
            document.getElementById('resultArea').innerHTML = `
                <div class="p-8 text-center bg-elevate-soft border border-slate-200 rounded-[2rem]">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm">
                        <i class="ph-duotone ph-ghost text-4xl"></i>
                    </div>
                    <h4 class="text-lg font-black text-elevate-dark">Data Tidak Ditemukan</h4>
                    <p class="text-elevate-dark/60 text-sm mt-1">${message}</p>
                </div>`;
        }
    </script>
</x-app-layout>