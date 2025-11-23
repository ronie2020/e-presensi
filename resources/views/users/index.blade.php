{{-- Halaman ini adalah tampilan untuk resources/views/users/index.blade.php --}}
<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header Page --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                Manajemen Pengguna
            </h1>
            <p class="text-gray-500 mt-1">
                Kelola akun akses untuk Guru, Staf, dan Administrator sistem.
            </p>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center gap-3 shadow-sm">
                <div class="p-2 bg-rose-100 rounded-full text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <ul class="list-disc list-inside text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- KOLOM KIRI: FORM TAMBAH USER --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-cyan-100 overflow-hidden relative group hover:shadow-lg hover:shadow-cyan-100/50 transition-all duration-300 h-fit sticky top-6">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-cyan-500"></div>
                    <div class="p-6 md:p-8 relative z-10">
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-cyan-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-800">User Baru</h3>
                                <p class="text-xs text-gray-500">Tambah akses sistem</p>
                            </div>
                        </div>

                        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name') }}" required 
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-cyan-500 text-sm py-3 font-bold text-gray-700 transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Email Login</label>
                                <input type="email" name="email" value="{{ old('email') }}" required 
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-cyan-500 text-sm py-3 font-medium transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Peran (Role)</label>
                                <div class="relative">
                                    <select name="role" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-cyan-500 text-sm py-3 font-medium transition-colors appearance-none">
                                        <option value="Guru" {{ old('role') == 'Guru' ? 'selected' : '' }}>Guru</option>
                                        <option value="Wali Kelas" {{ old('role') == 'Wali Kelas' ? 'selected' : '' }}>Wali Kelas</option>
                                        <option value="Guru Piket" {{ old('role') == 'Guru Piket' ? 'selected' : '' }}>Guru Piket</option>
                                        <option value="Kepala Sekolah" {{ old('role') == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                        <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin (IT)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Password</label>
                                    <input type="password" name="password" required 
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-cyan-500 text-sm py-3 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Konfirmasi</label>
                                    <input type="password" name="password_confirmation" required 
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-cyan-500 focus:ring-cyan-500 text-sm py-3 transition-colors">
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 px-6 bg-cyan-600 text-white font-bold rounded-xl hover:bg-cyan-700 transition-all shadow-lg shadow-cyan-200 flex items-center justify-center gap-2 mt-2 group-hover:translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Pengguna
                            </button>
                        </form>
                    </div>
                    {{-- Background Decor --}}
                    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-cyan-50 rounded-full blur-2xl opacity-50 pointer-events-none"></div>
                </div>
            </div>

            {{-- KOLOM KANAN: DAFTAR USER --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
                    
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="text-lg font-black text-gray-800">Daftar Pengguna Aktif</h3>
                        <span class="text-xs font-bold bg-white px-2 py-1 rounded border border-gray-200 text-gray-500">{{ $users->total() }} Akun</span>
                    </div>
                    
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full text-left border-collapse">
                            <thead class="bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Identitas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Peran</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-cyan-50/30 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 text-gray-600 font-bold flex items-center justify-center text-xs group-hover:from-cyan-100 group-hover:to-cyan-200 group-hover:text-cyan-700 transition-all shadow-inner">
                                                    {{ substr($user->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-800 text-sm">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $badgeClass = match($user->role) {
                                                    'Admin' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                    'Kepala Sekolah' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                    'Wali Kelas' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                    'Guru Piket' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                    default => 'bg-emerald-100 text-emerald-700 border-emerald-200', // Guru
                                                };
                                            @endphp
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold border {{ $badgeClass }}">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if(Auth::id() != $user->id)
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-gray-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-all" title="Hapus User">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs font-bold text-gray-300 italic mr-2 select-none">(Anda)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                </div>
                                                <p class="text-sm font-medium">Belum ada data pengguna lain.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="p-4 border-t border-gray-100">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>