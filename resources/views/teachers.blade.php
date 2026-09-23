@extends('layouts.public')

@section('title', 'Direktori Pengajar - ' . config('app.name', 'SMP Negeri 3 Lakbok'))

@push('styles')
    <style>
        /* Mencegah elemen berkedip saat AlpineJS belum siap */
        [x-cloak] { display: none !important; }

        /* Animasi Custom Khusus Halaman Ini */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        .animate-blob { animation: blob 7s infinite; }
        @keyframes blob { 
            0% { transform: translate(0px, 0px) scale(1); } 
            33% { transform: translate(30px, -50px) scale(1.1); } 
            66% { transform: translate(-20px, 20px) scale(0.9); } 
            100% { transform: translate(0px, 0px) scale(1); } 
        }
        
        /* Custom Scrollbar untuk Modal */
        .modal-scroll::-webkit-scrollbar { width: 6px; }
        .modal-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .modal-scroll::-webkit-scrollbar-thumb { background: #56bbf1; border-radius: 10px; } /* Warna elevate-accent */
    </style>
@endpush

@section('content')
    {{-- Wrapper x-data: Memindahkan logika dari body ke sini agar tetap jalan di dalam layout --}}
    <div x-data="{ 
          modalOpen: false, 
          showQrModal: false,
          teacher: {},
          linkCopied: false,
          isSearching: false, 
          
          openModal(data) {
              this.teacher = data;
              this.modalOpen = true;
              this.showQrModal = false;
              this.linkCopied = false;
              document.body.style.overflow = 'hidden'; 
          },
          closeModal() {
              this.modalOpen = false;
              this.showQrModal = false;
              setTimeout(() => { this.teacher = {} }, 300);
              document.body.style.overflow = 'auto'; 
          },
          formatWA(phone) {
              if(!phone) return '';
              let number = phone.replace(/[^0-9]/g, '');
              if(number.startsWith('0')) number = '62' + number.substr(1);
              return 'https://wa.me/' + number;
          },
          formatSocialUrl(platform, value) {
              if (!value) return '';
              value = value.trim();
              if (value.startsWith('http')) return value;
              if (value.startsWith('@')) value = value.substring(1);
              
              if (platform === 'ig') return 'https://instagram.com/' + value;
              if (platform === 'fb') return 'https://facebook.com/' + value;
              if (platform === 'tiktok') return 'https://tiktok.com/@' + value;
              return value;
          },
           copyProfileLink() {
              if(!this.teacher.profile_url) return;
              
              const el = document.createElement('textarea');
              el.value = this.teacher.profile_url;
              document.body.appendChild(el);
              el.select();
              document.execCommand('copy');
              document.body.removeChild(el);
              
              this.linkCopied = true;
              setTimeout(() => { this.linkCopied = false; }, 3000);
          },

          // FITUR AJAX PENCARIAN REAL-TIME
          searchQuery: '{{ request('q') }}',
          searchCategory: '{{ request('kategori') }}',
          
          performSearch() {
              this.isSearching = true;
              const url = new URL('{{ route('teachers.index') }}');
              url.searchParams.set('q', this.searchQuery);
              url.searchParams.set('kategori', this.searchCategory);
              
              // Update URL browser tanpa reload
              window.history.pushState({}, '', url);

              fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                  .then(res => res.text())
                  .then(html => {
                      document.getElementById('teacher-grid-container').innerHTML = html;
                      this.isSearching = false;
                  })
                  .catch(err => { console.error('Error fetching data', err); this.isSearching = false; });
          }
      }">

        <!-- HEADER SECTION (Dark Glassmorphism) -->
        <div class="pt-32 pb-28 relative overflow-hidden -mt-24 border-b border-white/10">
            <!-- Animated Blobs / Glows -->
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-elevate-accent/15 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute bottom-0 right-10 w-72 h-72 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-enter">
                
                {{-- Badge Status --}}
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-elevate-accent text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-md shadow-sm">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-accent opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-elevate-accent"></span>
                    </span>
                    SDM Berkualitas & Berdedikasi
                </span>
                
                <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight">Direktori Tenaga Pendidik</h1>
                <p class="text-slate-300 text-lg max-w-2xl mx-auto mb-12 leading-relaxed font-medium">
                    Profil profesional guru dan staf pengajar SMP Negeri 3 Lakbok yang berkompeten dan berintegritas tinggi.
                </p>

                <!-- FORM PENCARIAN & FILTER (DENGAN AJAX) -->
                <form @submit.prevent="performSearch" class="max-w-2xl mx-auto relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-elevate-accent/40 to-blue-500/40 rounded-full blur-md opacity-40 group-hover:opacity-75 transition duration-500"></div>
                    
                    <div class="relative flex flex-col sm:flex-row bg-[#021124]/80 backdrop-blur-2xl rounded-[2rem] sm:rounded-full shadow-2xl transition-transform focus-within:scale-[1.02] border border-white/20">
                        
                        <!-- Dropdown Kategori -->
                        <div class="relative w-full sm:w-2/5 border-b sm:border-b-0 sm:border-r border-white/15">
                            <div class="absolute left-6 top-1/2 -translate-y-1/2 text-elevate-accent">
                                <i class="ph-bold ph-funnel text-xl"></i>
                            </div>
                            <select x-model="searchCategory" @change="performSearch" class="w-full pl-14 pr-10 py-4 bg-transparent border-0 focus:ring-0 text-sm font-bold text-white cursor-pointer appearance-none rounded-t-[2rem] sm:rounded-l-full sm:rounded-tr-none [&>option]:bg-[#021124] [&>option]:text-white">
                                <option value="">Semua Peran</option>
                                <option value="guru">Guru / Pendidik</option>
                                <option value="staf">Staf Tata Usaha</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                <i class="ph-bold ph-caret-down text-lg"></i>
                            </div>
                        </div>
                        
                        <!-- Input Pencarian -->
                        <div class="relative w-full sm:w-3/5">
                            <input x-model="searchQuery" @input.debounce.500ms="performSearch" type="text" placeholder="Ketik nama atau mapel..." class="w-full pl-6 pr-24 py-4 bg-transparent border-0 focus:ring-0 text-sm font-bold placeholder-slate-400 text-white rounded-b-[2rem] sm:rounded-r-full sm:rounded-bl-none">
                            
                            <!-- Tombol Reset & Submit -->
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1.5">
                                <a x-show="searchQuery || searchCategory" @click.prevent="searchQuery=''; searchCategory=''; performSearch()" href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-white/10 text-slate-300 hover:bg-rose-500/20 hover:text-rose-400 transition-colors" title="Reset Filter"><i class="ph-bold ph-x"></i></a>
                                <button type="submit" class="w-10 h-10 flex items-center justify-center bg-elevate-accent rounded-full text-elevate-dark hover:bg-white transition shadow-lg shadow-elevate-accent/30 hover:scale-110 active:scale-95">
                                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- MAIN CONTENT -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20 pb-20">
            
            {{-- FITUR TEACHER SPOTLIGHT --}}
            @if(isset($spotlightTeacher) && !request('q') && !request('kategori'))
                <div x-show="!isSearching" class="mb-16 bg-white/10 backdrop-blur-2xl rounded-[2.5rem] p-6 sm:p-10 border border-white/20 shadow-2xl flex flex-col md:flex-row items-center gap-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-elevate-accent/20 to-transparent rounded-bl-full pointer-events-none"></div>
                    
                    <div class="w-32 h-32 md:w-48 md:h-48 rounded-full border-4 border-elevate-accent/50 shrink-0 overflow-hidden shadow-2xl group-hover:scale-105 transition-transform duration-500 bg-white/5">
                        <img src="{{ asset('storage/' . $spotlightTeacher->photo_path) }}" class="w-full h-full object-cover">
                    </div>
                    
                    <div class="flex-1 text-center md:text-left relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-elevate-accent/20 text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-3 border border-elevate-accent/30">
                            <i class="ph-fill ph-star text-amber-400"></i> Teacher Spotlight
                        </div>
                        <h2 class="text-2xl md:text-4xl font-black text-white mb-2">{{ $spotlightTeacher->name }}</h2>
                        <p class="text-sm font-bold text-elevate-accent uppercase tracking-widest mb-4">{{ $spotlightTeacher->position ?: 'Guru Berprestasi' }}</p>
                        <p class="text-slate-300 mb-6 max-w-2xl text-sm md:text-base leading-relaxed line-clamp-2">"{{ $spotlightTeacher->bio ?: 'Berdedikasi dalam mendidik dan membimbing siswa mencapai potensi terbaik mereka.' }}"</p>
                        
                        <div class="flex items-center justify-center md:justify-start gap-4">
                            <a href="{{ route('teachers.show', $spotlightTeacher->id) }}" class="px-6 py-2.5 bg-elevate-accent text-elevate-dark font-black text-sm rounded-xl shadow-lg shadow-elevate-accent/30 hover:bg-white transition-all">
                                Lihat Profil Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- EFEK SKELETON LOADING -->
            <div x-show="isSearching" x-cloak class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                @for($i = 1; $i <= 8; $i++)
                    <div class="bg-white/10 backdrop-blur-xl rounded-[2rem] overflow-hidden shadow-sm border border-white/15 flex flex-col h-full relative animate-pulse">
                        <div class="aspect-[4/5] sm:aspect-square bg-white/10 relative overflow-hidden"></div>
                        <div class="p-5 text-center flex-1 flex flex-col relative bg-transparent">
                            <div class="absolute -top-4 left-0 right-0 flex justify-center px-4">
                                <div class="w-20 h-6 bg-white/15 rounded-full shadow-sm border-2 border-white/20"></div>
                            </div>
                            <div class="mt-4 mb-2 flex flex-col items-center gap-2">
                                <div class="w-3/4 h-5 bg-white/20 rounded-md"></div>
                                <div class="w-1/2 h-3 bg-white/10 rounded-md mt-1"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- KONTEN AJAX AKAN DIRENDER DI SINI -->
            <div x-show="!isSearching" id="teacher-grid-container" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @include('landing.partials.teacher-grid', ['teachers' => $teachers])
            </div>
            
        </div>

        <!-- MODAL DETAIL GURU (POPUP) -->
        <div x-show="modalOpen" x-cloak style="display: none;" class="fixed inset-0 z-[99999] flex items-start justify-center p-4 pt-24 sm:p-6 sm:pt-28 pb-4 sm:pb-8" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-[#021124]/85 backdrop-blur-md transition-opacity" @click="closeModal()"></div>

            <!-- Wrapper Modal -->
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative flex flex-col w-full max-w-4xl max-h-[calc(100vh-7rem)] sm:max-h-[calc(100vh-9rem)] bg-[#021124]/95 backdrop-blur-2xl rounded-[2.5rem] text-left shadow-2xl border border-white/20 overflow-hidden transform transition-all text-white" @click.away="closeModal()">
                
                <button @click="closeModal()" class="absolute top-4 right-4 z-20 bg-white/10 hover:bg-rose-500/20 backdrop-blur p-2.5 rounded-full text-slate-300 hover:text-rose-400 transition-all shadow-sm border border-white/10"><i class="ph-bold ph-x text-xl"></i></button>

                <!-- Area Konten yang Bisa di-Scroll secara internal -->
                <div class="flex-1 overflow-y-auto modal-scroll w-full bg-transparent">
                    <div class="flex flex-col md:flex-row min-h-full">
                        
                        <!-- KIRI: FOTO & IDENTITAS UTAMA -->
                        <div class="md:w-5/12 bg-white/5 p-6 md:p-10 flex flex-col items-center justify-start text-center relative border-b md:border-b-0 md:border-r border-white/10 shrink-0">
                            <!-- Foto Profil Besar -->
                            <div class="w-48 h-48 rounded-full p-1.5 bg-white/10 border-2 border-dashed border-elevate-accent shadow-xl mb-6 relative group">
                               <div class="w-full h-full rounded-full overflow-hidden relative">
                                    <template x-if="teacher.photo_url">
                                        <img :src="teacher.photo_url" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" alt="Foto Guru">
                                    </template>
                                    <template x-if="!teacher.photo_url">
                                        <div class="w-full h-full bg-white/10 flex items-center justify-center text-elevate-accent text-6xl font-black uppercase select-none shadow-inner">
                                            <span x-text="teacher.name ? teacher.name.substring(0,2) : 'GU'"></span>
                                        </div>
                                    </template>
                                </div>
                                <div class="absolute bottom-3 right-3 bg-emerald-500 border-4 border-[#021124] w-7 h-7 rounded-full shadow-md" title="Status Aktif"></div>
                            </div>

                            <!-- Nama & Jabatan -->
                            <h2 class="text-2xl font-black text-white leading-tight mb-2" x-text="teacher.name"></h2>
                            <div class="mb-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-elevate-accent/20 text-elevate-accent text-xs font-bold uppercase tracking-wider border border-elevate-accent/30">
                                    <i class="ph-fill ph-chalkboard-teacher mr-1.5"></i>
                                    <span x-text="teacher.position || 'Tenaga Pendidik'"></span>
                                </span>
                            </div>
                            
                             <!-- Tombol Portofolio -->
                            <a :href="teacher.profile_url" class="w-full mb-3 py-3.5 bg-elevate-accent hover:bg-white text-elevate-dark font-black rounded-2xl shadow-lg shadow-elevate-accent/20 transition-all flex items-center justify-center gap-2 group hover:-translate-y-0.5">
                                <i class="ph-bold ph-user-circle text-xl group-hover:scale-110 transition-transform"></i>
                                Lihat Portofolio & Karya
                            </a>

                            <!-- Tombol Kontak WA -->
                            <template x-if="teacher.phone">
                                <a :href="formatWA(teacher.phone)" target="_blank" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2 group hover:-translate-y-0.5">
                                    <i class="ph-fill ph-whatsapp-logo text-xl group-hover:scale-110 transition-transform"></i>
                                    Hubungi via WhatsApp
                                </a>
                            </template>
                            <template x-if="!teacher.phone">
                                <button disabled class="w-full py-3.5 bg-white/5 text-slate-500 rounded-2xl font-bold cursor-not-allowed flex items-center justify-center gap-2 border border-white/10">
                                    <i class="ph-slash ph-phone-slash text-xl"></i> Kontak Tidak Tersedia
                                </button>
                            </template>
                        </div>

                        <!-- KANAN: DETAIL INFO -->
                        <div class="md:w-7/12 p-6 md:p-10 bg-transparent">
                            
                             <!-- Info Akademik -->
                            <div class="mb-8">
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="ph-bold ph-info text-elevate-accent"></i> Informasi Akademik
                                </h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="bg-white/5 p-4 rounded-2xl border border-white/10 flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-white/10 text-elevate-accent shadow-sm flex items-center justify-center shrink-0 border border-white/10">
                                            <i class="ph-duotone ph-briefcase text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-400 font-bold uppercase mb-0.5">Jabatan / Mapel</p>
                                            <p class="font-bold text-white text-lg" x-text="teacher.position || 'Tenaga Pendidik'"></p>
                                        </div>
                                    </div>
                                    <div class="bg-white/5 p-4 rounded-2xl border border-white/10 flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-white/10 text-slate-300 shadow-sm flex items-center justify-center shrink-0 border border-white/10">
                                            <i class="ph-duotone ph-identification-card text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-400 font-bold uppercase mb-0.5">NIP & Pangkat</p>
                                            
                                            <template x-if="teacher.nip && teacher.nip !== '-'">
                                                <p class="font-mono font-bold text-white text-sm" x-text="teacher.nip"></p>
                                            </template>
                                            <template x-if="!teacher.nip || teacher.nip === '-'">
                                                <p class="text-slate-500 text-sm italic">Belum ada NIP</p>
                                            </template>
                                            
                                            <template x-if="teacher.pangkat && teacher.pangkat !== '-'">
                                                <span class="inline-block mt-1 px-2.5 py-0.5 bg-elevate-accent/20 border border-elevate-accent/30 text-elevate-accent text-[10px] font-bold rounded-lg" x-text="teacher.pangkat"></span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pesan & Kesan (Bio) -->
                            <div class="mb-8">
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <i class="ph-bold ph-quotes text-elevate-accent"></i> Pesan & Kesan
                                </h3>
                                <div class="relative bg-white/5 p-6 rounded-[1.5rem] border border-white/10 shadow-sm">
                                    <i class="ph-fill ph-quotes text-4xl text-white/5 absolute top-4 right-4"></i>
                                    <p class="text-slate-200 text-sm italic leading-relaxed relative z-10" x-text="teacher.bio || 'Belum ada pesan dan kesan yang ditambahkan.'"></p>
                                </div>
                            </div>

                            <!-- Keahlian & Hobi -->
                            <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 gap-6" x-show="teacher.keahlian || teacher.hobi">
                                <div x-show="teacher.keahlian">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                        <i class="ph-bold ph-star text-amber-400"></i> Keahlian
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="item in (teacher.keahlian || '').split(',')" :key="item">
                                            <span class="px-2.5 py-1 bg-amber-400/10 text-amber-300 border border-amber-400/30 rounded-lg text-[10px] font-bold uppercase tracking-wider" x-text="item.trim()" x-show="item.trim() !== ''"></span>
                                        </template>
                                    </div>
                                </div>
                                <div x-show="teacher.hobi">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                        <i class="ph-bold ph-heart text-rose-400"></i> Hobi
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="item in (teacher.hobi || '').split(',')" :key="item">
                                            <span class="px-2.5 py-1 bg-rose-400/10 text-rose-300 border border-rose-400/30 rounded-lg text-[10px] font-bold uppercase tracking-wider" x-text="item.trim()" x-show="item.trim() !== ''"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Sosial Media, Unduh CV, & Copy Link -->
                            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mt-8 pt-6 border-t border-white/10">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <!-- Sosial Media -->
                                    <template x-if="teacher.instagram">
                                        <a :href="formatSocialUrl('ig', teacher.instagram)" target="_blank" class="w-10 h-10 rounded-full bg-white/10 hover:bg-elevate-accent hover:text-elevate-dark flex items-center justify-center text-slate-300 transition-all hover:-translate-y-1 hover:shadow-md border border-white/10" title="Instagram"><i class="ph-fill ph-instagram-logo text-xl"></i></a>
                                    </template>
                                    <template x-if="teacher.facebook">
                                        <a :href="formatSocialUrl('fb', teacher.facebook)" target="_blank" class="w-10 h-10 rounded-full bg-white/10 hover:bg-elevate-accent hover:text-elevate-dark flex items-center justify-center text-slate-300 transition-all hover:-translate-y-1 hover:shadow-md border border-white/10" title="Facebook"><i class="ph-fill ph-facebook-logo text-xl"></i></a>
                                    </template>
                                    <template x-if="teacher.tiktok">
                                        <a :href="formatSocialUrl('tiktok', teacher.tiktok)" target="_blank" class="w-10 h-10 rounded-full bg-white/10 hover:bg-elevate-accent hover:text-elevate-dark flex items-center justify-center text-slate-300 transition-all hover:-translate-y-1 hover:shadow-md border border-white/10" title="TikTok"><i class="ph-fill ph-tiktok-logo text-xl"></i></a>
                                    </template>

                                    <!-- Tombol Bagikan Link Profil -->
                                    <button @click="copyProfileLink()" class="ml-2 px-3 py-2 rounded-xl border border-white/20 text-slate-300 font-bold text-xs hover:bg-white/10 transition-colors flex items-center gap-1.5 focus:outline-none" :class="linkCopied ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : ''">
                                        <i class="ph-bold text-base" :class="linkCopied ? 'ph-check-circle' : 'ph-link'"></i>
                                        <span x-text="linkCopied ? 'Disalin!' : 'Bagikan'"></span>
                                    </button>
                             <!-- Box QR Code Kontak (Jika Ditekan) -->
                             <div x-show="showQrModal" x-transition class="mt-6 p-4 rounded-2xl bg-white/10 border border-white/20 text-center flex flex-col items-center justify-center">
                                 <p class="text-xs font-bold text-white mb-2 flex items-center gap-1.5"><i class="ph-bold ph-qr-code text-emerald-400"></i> Scan QR untuk Simpan Kontak</p>
                                 <div class="p-2 bg-white rounded-xl shadow-lg inline-block">
                                     <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + encodeURIComponent(teacher.vcard_url || teacher.profile_url)" alt="QR Kontak Guru" class="w-40 h-40 object-contain">
                                 </div>
                                 <p class="text-[10px] text-slate-300 mt-2 font-medium">Buka Kamera / Pemindai QR di HP Anda</p>
                             </div>

                         </div>
                     </div>
                 </div>

                 <!-- Action Buttons -->
                 <div class="p-6 bg-white/5 border-t border-white/10 flex flex-wrap items-center justify-center sm:justify-end gap-3 rounded-b-[2rem]">
                     <button @click="closeModal()" type="button" class="px-5 py-2.5 rounded-xl font-bold text-slate-300 bg-white/10 border border-white/15 hover:bg-white/20 transition-colors order-last sm:order-first text-sm">
                         Tutup
                     </button>
                     
                     <button type="button" @click="showQrModal = !showQrModal" x-show="teacher.vcard_url" class="px-4 py-2.5 rounded-xl font-bold text-slate-200 bg-white/10 border border-white/20 hover:bg-white/20 transition-all text-sm flex items-center gap-2 shadow-sm" :class="showQrModal ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : ''">
                         <i class="ph-bold ph-qr-code text-emerald-400"></i> <span x-text="showQrModal ? 'Sembunyikan QR' : 'QR Kontak'"></span>
                     </button>

                     <a :href="teacher.vcard_url" x-show="teacher.vcard_url" class="px-5 py-2.5 rounded-xl font-bold text-emerald-300 bg-emerald-500/15 border border-emerald-500/30 hover:bg-emerald-500 hover:text-white transition-all text-sm flex items-center gap-2 shadow-sm">
                         <i class="ph-bold ph-address-book"></i> Simpan Kontak
                     </a>

                     <a :href="teacher.cv_url" x-show="teacher.cv_url" target="_blank" class="px-5 py-2.5 rounded-xl font-bold text-elevate-accent bg-elevate-accent/15 border border-elevate-accent/30 hover:bg-elevate-accent hover:text-elevate-dark transition-all text-sm flex items-center gap-2 shadow-sm">
                         <i class="ph-bold ph-download-simple"></i> Unduh CV
                     </a>

                     <a :href="teacher.profile_url" class="px-6 py-2.5 rounded-xl font-bold text-elevate-dark bg-elevate-accent hover:bg-white transition-all text-sm flex items-center gap-2 shadow-lg shadow-elevate-accent/20">
                         <i class="ph-bold ph-user-circle"></i> Lihat Profil <span class="hidden sm:inline">Lengkap</span>
                     </a>
                 </div>
             </div>
        </div>
    </div>
@endsection