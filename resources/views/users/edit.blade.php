<x-app-layout>
    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto"> <!-- Container diperlebar sedikit -->
            
            <!-- Header & Tombol Kembali -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-black text-gray-800 tracking-tight">Edit Pengguna</h1>
                    <p class="text-gray-500 text-sm mt-1">Perbarui data profil, kontak, jabatan, atau password.</p>
                </div>
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition shadow-sm">
                    &larr; Kembali
                </a>
            </div>

            <!-- Form Edit -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-cyan-400 to-blue-500"></div>
                
                <div class="p-8">
                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="{ role: '{{ $user->role }}' }">
                        @csrf
                        @method('PUT')

                        <!-- Preview Foto & Info Dasar -->
                        <div class="flex flex-col md:flex-row md:items-center gap-6 pb-8 border-b border-gray-100">
                            <div class="shrink-0 mx-auto md:mx-0">
                                @if($user->photo_path)
                                    <img src="{{ asset('storage/' . $user->photo_path) }}" class="w-24 h-24 rounded-full object-cover border-4 border-gray-50 shadow-md">
                                @else
                                    <div class="w-24 h-24 rounded-full bg-cyan-50 flex items-center justify-center text-cyan-600 text-2xl font-bold border-4 border-white shadow-md">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            <div class="text-center md:text-left">
                                <h3 class="font-bold text-gray-800 text-xl">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                <span class="inline-flex mt-2 px-2.5 py-1 rounded-md bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100 uppercase tracking-wide">
                                    {{ $user->role }}
                                </span>
                            </div>
                        </div>

                        <!-- 1. DATA AKUN UTAMA -->
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4 border-l-4 border-cyan-500 pl-3">Data Akun & Login</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-cyan-500 text-sm py-3 font-bold text-gray-700">
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Email Login</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-cyan-500 text-sm py-3 font-medium">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Peran (Role)</label>
                                    <select name="role" x-model="role" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-cyan-500 text-sm py-3 font-medium">
                                        <option value="Guru">Guru</option>
                                        <option value="Wali Kelas">Wali Kelas</option>
                                        <option value="Guru Piket">Guru Piket</option>
                                        <option value="Kepala Sekolah">Kepala Sekolah</option>
                                        <option value="Admin">Admin (IT)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 2. DATA PROFIL GURU (Hanya jika bukan admin) -->
                        <div x-show="role !== 'Admin'" class="pt-2">
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4 border-l-4 border-emerald-500 pl-3">Data Profil Guru</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Jabatan / Mapel</label>
                                    <input type="text" name="position" value="{{ old('position', $user->position) }}" placeholder="Contoh: Guru Matematika"
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-cyan-500 text-sm py-3">
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">NIP (Nomor Induk)</label>
                                    <input type="text" name="nip" value="{{ old('nip', $user->nip ?? '') }}" placeholder="Contoh: 19800101..."
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-cyan-500 text-sm py-3">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Bio Singkat</label>
                                    <textarea name="bio" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-cyan-500 text-sm py-3" placeholder="Tulis motto atau deskripsi singkat...">{{ old('bio', $user->bio) }}</textarea>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Upload Foto Baru</label>
                                    <input type="file" name="photo" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                                </div>
                            </div>
                        </div>

                        <!-- 3. KONTAK & MEDIA SOSIAL (BARU) -->
                        <div x-show="role !== 'Admin'" class="pt-2">
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4 border-l-4 border-purple-500 pl-3">Kontak & Media Sosial</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                                
                                <!-- No HP / WhatsApp -->
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                        <i class="ph-fill ph-whatsapp-logo text-green-500 text-lg"></i> WhatsApp / HP
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="08xxxxxx"
                                           class="w-full rounded-xl border-gray-200 focus:border-green-500 focus:ring-green-500 text-sm py-3">
                                </div>

                                <!-- Instagram -->
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                        <i class="ph-fill ph-instagram-logo text-pink-500 text-lg"></i> Instagram (Username)
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm">@</span>
                                        <input type="text" name="instagram" value="{{ old('instagram', $user->instagram ?? '') }}" placeholder="username"
                                               class="w-full rounded-xl border-gray-200 pl-8 focus:border-pink-500 focus:ring-pink-500 text-sm py-3">
                                    </div>
                                </div>

                                <!-- TikTok -->
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                        <i class="ph-fill ph-tiktok-logo text-black text-lg"></i> TikTok (Username)
                                    </label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm">@</span>
                                        <input type="text" name="tiktok" value="{{ old('tiktok', $user->tiktok ?? '') }}" placeholder="username"
                                               class="w-full rounded-xl border-gray-200 pl-8 focus:border-black focus:ring-black text-sm py-3">
                                    </div>
                                </div>

                                <!-- Facebook -->
                                <div class="col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                        <i class="ph-fill ph-facebook-logo text-blue-600 text-lg"></i> Facebook (Nama/URL)
                                    </label>
                                    <input type="text" name="facebook" value="{{ old('facebook', $user->facebook ?? '') }}" placeholder="Nama Profil"
                                           class="w-full rounded-xl border-gray-200 focus:border-blue-600 focus:ring-blue-600 text-sm py-3">
                                </div>
                            </div>
                        </div>

                        <!-- 4. GANTI PASSWORD -->
                        <div class="pt-6 border-t border-gray-100">
                            <button type="button" x-data="{ open: false }" @click="open = !open" class="text-sm font-bold text-rose-500 flex items-center gap-2 hover:underline">
                                <i class="ph-bold ph-lock-key"></i> Ganti Password Akun?
                            </button>
                            
                            <div x-show="open" x-transition class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6 bg-rose-50/50 p-6 rounded-2xl border border-rose-100" style="display: none;">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Password Baru</label>
                                    <input type="password" name="password" class="w-full rounded-xl border-gray-200 bg-white focus:border-rose-500 text-sm py-3">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Konfirmasi</label>
                                    <input type="password" name="password_confirmation" class="w-full rounded-xl border-gray-200 bg-white focus:border-rose-500 text-sm py-3">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 pt-6">
                            <a href="{{ route('users.index') }}" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">Batal</a>
                            <button type="submit" class="px-6 py-3 rounded-xl bg-cyan-600 text-white font-bold hover:bg-cyan-700 transition shadow-lg shadow-cyan-200 flex items-center gap-2">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>