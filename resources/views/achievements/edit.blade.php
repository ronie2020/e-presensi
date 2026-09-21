<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6 flex items-center gap-4">
                <a href="{{ route('achievements.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#021124]/80 border border-white/15 text-slate-300 hover:text-white hover:border-[#56bbf1]/50 hover:bg-[#031d3d] transition-all shadow-sm group">
                    <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-white">Edit Prestasi</h1>
                    <p class="text-slate-400 text-sm font-medium">Perbarui data penghargaan.</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] via-sky-500 to-[#56bbf1]"></div>
                <div class="p-6 md:p-8 mt-2">
                    <form action="{{ route('achievements.update', $achievement->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" 
                          x-data="{ type: '{{ old('type', $achievement->type) }}', imgPreview: '{{ $achievement->photo_path ? asset('storage/'.$achievement->photo_path) : '' }}' }">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pemenang</label>
                            <div class="grid grid-cols-3 gap-1 p-1 bg-[#021124]/80 rounded-xl border border-white/10">
                                <button type="button" @click="type = 'Siswa'" :class="type === 'Siswa' ? 'bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] text-white shadow-md' : 'text-slate-400 hover:text-white'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Siswa</button>
                                <button type="button" @click="type = 'Guru'" :class="type === 'Guru' ? 'bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] text-white shadow-md' : 'text-slate-400 hover:text-white'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Guru</button>
                                <button type="button" @click="type = 'Sekolah'" :class="type === 'Sekolah' ? 'bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] text-white shadow-md' : 'text-slate-400 hover:text-white'" class="py-2.5 rounded-lg text-xs font-bold transition-all duration-200">Sekolah</button>
                            </div>
                            <input type="hidden" name="type" x-model="type">
                        </div>

                        <div x-show="type === 'Siswa'" x-transition>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Siswa</label>
                            <div class="relative">
                                <i class="ph-bold ph-student absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <select name="student_id" class="w-full pl-11 pr-10 py-3 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white transition-colors appearance-none cursor-pointer text-sm">
                                    <option value="" class="bg-[#021124] text-white">-- Pilih Siswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" class="bg-[#021124] text-white" {{ (old('student_id') ?? $achievement->student_id) == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} ({{ $student->schoolClass->name ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                            </div>
                            @error('student_id') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        
                        <div x-show="type !== 'Siswa'" x-transition style="display: none;">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Pemenang / Tim</label>
                            <div class="relative">
                                <i class="ph-bold ph-users absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="name_manual" value="{{ old('name_manual', $achievement->name_manual) }}" placeholder="Contoh: Tim Futsal Guru" class="w-full pl-11 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white py-3 transition-colors text-sm placeholder:text-slate-500">
                            </div>
                            @error('name_manual') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Prestasi</label>
                            <div class="relative">
                                <i class="ph-bold ph-medal absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="title" value="{{ old('title', $achievement->title) }}" required placeholder="Juara 1 Lomba..." class="w-full pl-11 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white py-3 transition-colors text-sm placeholder:text-slate-500">
                            </div>
                            @error('title') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tingkat</label>
                                <div class="relative">
                                    <select name="level" class="w-full pl-3 pr-8 py-3 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white text-xs appearance-none">
                                        @foreach(['Sekolah', 'Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'] as $lvl)
                                            <option value="{{ $lvl }}" class="bg-[#021124] text-white" {{ (old('level') ?? $achievement->level) == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                        @endforeach
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                <input type="date" name="date" value="{{ old('date', \Carbon\Carbon::parse($achievement->date)->format('Y-m-d')) }}" class="w-full px-3 py-3 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white text-xs [color-scheme:dark]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Dokumentasi Foto</label>
                            <div class="relative group">
                                <input type="file" name="photo" accept="image/*" @change="if($event.target.files.length > 0) { imgPreview = URL.createObjectURL($event.target.files[0]) }" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="border-2 border-dashed border-white/15 rounded-2xl p-4 text-center transition-all group-hover:border-[#56bbf1] group-hover:bg-[#56bbf1]/5" :class="{'border-[#56bbf1] bg-[#56bbf1]/5': imgPreview}">
                                    <div x-show="!imgPreview" class="space-y-2">
                                        <i class="ph-duotone ph-image text-3xl text-slate-400 group-hover:text-[#56bbf1] transition-colors"></i>
                                        <p class="text-[10px] text-slate-400 font-bold">Upload Foto Baru</p>
                                    </div>
                                    <div x-show="imgPreview" style="display: none;">
                                        <img :src="imgPreview" class="h-32 w-full object-contain rounded-xl mx-auto">
                                        <p class="text-[10px] text-slate-400 font-bold mt-2">Klik area ini untuk mengganti foto</p>
                                    </div>
                                </div>
                            </div>
                            @if($achievement->photo_path)
                                <p class="text-xs text-slate-400 mt-2 ml-1"><i class="ph-fill ph-info text-[#56bbf1]"></i> Kosongkan jika tidak ingin mengganti foto saat ini.</p>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link Video (Opsional)</label>
                                <div class="relative">
                                    <i class="ph-bold ph-youtube-logo absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="url" name="video_link" value="{{ old('video_link', $achievement->video_link) }}" placeholder="https://..." class="w-full pl-11 rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-bold text-white py-3 transition-colors text-xs placeholder:text-slate-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Sertifikat (Opsional)</label>
                                <div class="relative">
                                    <input type="file" name="certificate" accept=".pdf,image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-2.5 file:px-3 file:rounded-xl file:border-0 file:font-bold file:bg-[#56bbf1]/10 file:text-[#56bbf1] bg-[#021124]/90 rounded-2xl border border-white/15 hover:file:bg-[#56bbf1]/20 transition-colors cursor-pointer hover:border-[#56bbf1]">
                                </div>
                                @if($achievement->certificate_path)
                                    <p class="text-[10px] text-[#56bbf1] mt-2 ml-1 font-bold"><a href="{{ asset('storage/'.$achievement->certificate_path) }}" target="_blank" class="hover:underline">Lihat Sertifikat Saat Ini</a></p>
                                @endif
                            </div>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <a href="{{ route('achievements.index') }}" class="flex-1 py-3.5 px-4 bg-white/5 border border-white/10 text-slate-300 font-bold rounded-2xl hover:bg-white/10 hover:text-white transition-all flex items-center justify-center gap-2 text-center">
                                Batal
                            </a>
                            <button type="submit" class="flex-[2] py-3.5 px-4 bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] text-white font-bold rounded-2xl hover:brightness-110 transition-all shadow-lg shadow-sky-950/50 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>