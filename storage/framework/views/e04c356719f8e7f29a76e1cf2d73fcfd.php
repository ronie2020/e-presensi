<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            
            <a href="<?php echo e(route('library.books.index')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-elevate-dark/60 hover:text-elevate-primary mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Katalog
            </a>

            
            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-700">Terdapat Kesalahan Input</h3>
                        <ul class="list-disc list-inside text-xs text-rose-600 mt-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="bg-elevate-gradient-main rounded-[2.5rem] p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 relative overflow-hidden border border-white/60">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/40 rounded-full blur-[60px] translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
                <div class="relative z-10">
                    <h1 class="text-3xl font-black tracking-tight mb-2">Tambah Buku Baru</h1>
                    <p class="text-elevate-dark/80 text-sm max-w-xl leading-relaxed font-semibold">
                        Masukkan identitas buku induk. Sistem akan otomatis memproduksi barcode untuk masing-masing fisik buku sesuai jumlah yang Anda tentukan.
                    </p>
                </div>
            </div>

            
            <form action="<?php echo e(route('library.books.store')); ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-xl shadow-slate-200/50 border border-slate-100">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-elevate-soft p-6 rounded-[2rem] border border-slate-200">
                            <h3 class="text-xs font-black text-elevate-dark uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-white text-elevate-primary flex items-center justify-center text-[10px] shadow-sm">1</span>
                                Identitas Buku Induk
                            </h3>
                            
                            <div class="space-y-5">
                                
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Kode Buku / ISBN (Induk) <span class="text-rose-500">*</span></label>
                                    <div class="flex gap-2">
                                        <div class="relative flex-1 group">
                                            <i class="ph-bold ph-barcode absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                            <input type="text" name="book_code" id="book_code" required value="<?php echo e(old('book_code')); ?>"
                                                class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-mono font-bold text-elevate-dark shadow-sm transition-all" placeholder="Misal: 9786022828">
                                        </div>
                                        <button type="button" onclick="startScanner()" class="px-4 bg-white border border-slate-200 text-elevate-dark/60 hover:text-elevate-primary hover:border-elevate-primary rounded-2xl transition shadow-sm" title="Scan pakai Kamera">
                                            <i class="ph-bold ph-camera text-xl"></i>
                                        </button>
                                    </div>
                                    <p class="text-[10px] text-elevate-dark/50 mt-2 ml-1 font-medium"><i class="ph-bold ph-info text-elevate-primary"></i> Ketik manual atau scan barcode dari sampul buku. Sistem akan men-generate kode eksemplar tambahan (-01, -02).</p>
                                </div>

                                
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Judul Buku <span class="text-rose-500">*</span></label>
                                    <input type="text" name="title" required value="<?php echo e(old('title')); ?>" placeholder="Contoh: Laskar Pelangi"
                                        class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm">
                                </div>

                                
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Kategori / DDC <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <select name="category_id" required class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm cursor-pointer appearance-none">
                                            <option value="">-- Pilih Kategori --</option>
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                                    <?php echo e($category->code); ?> - <?php echo e($category->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Pengarang</label>
                                        <input type="text" name="author" value="<?php echo e(old('author')); ?>" placeholder="Nama Penulis"
                                            class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Penerbit</label>
                                        <input type="text" name="publisher" value="<?php echo e(old('publisher')); ?>" placeholder="Nama Penerbit"
                                            class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm">
                                    </div>
                                </div>

                                
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Tahun Terbit</label>
                                    <input type="number" name="year" value="<?php echo e(old('year')); ?>" placeholder="YYYY" min="1900" max="<?php echo e(date('Y') + 1); ?>"
                                        class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm">
                                </div>

                                
                                <div class="mt-4">
                                    <label class="flex items-center gap-3 p-4 border border-slate-200 bg-white rounded-2xl cursor-pointer hover:border-elevate-accent transition shadow-sm">
                                        <input type="checkbox" name="is_textbook" value="1" <?php echo e(old('is_textbook') ? 'checked' : ''); ?> class="w-5 h-5 text-elevate-primary border-slate-300 rounded focus:ring-elevate-accent cursor-pointer">
                                        <div>
                                            <span class="block text-sm font-bold text-elevate-dark">Ini Buku Paket / Pelajaran</span>
                                            <span class="block text-xs text-elevate-dark/60 mt-0.5">Buku paket bisa dipinjam massal selama 1 tahun.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="lg:col-span-5 space-y-6">
                        
                        
                        <div class="bg-elevate-soft p-6 rounded-[2rem] border border-slate-200">
                            <h3 class="text-xs font-black text-elevate-dark uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-white text-elevate-primary flex items-center justify-center text-[10px] shadow-sm">2</span>
                                Fisik & Eksemplar
                            </h3>
                            
                            <div class="space-y-5">
                                
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Jumlah Fisik Buku / Eksemplar <span class="text-rose-500">*</span></label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-stack absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                        <input type="number" name="jumlah_buku" required min="1" max="500" value="<?php echo e(old('jumlah_buku', 1)); ?>"
                                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark shadow-sm transition-all" placeholder="Misal: 32">
                                    </div>
                                    <p class="text-[10px] text-elevate-dark/50 mt-2 ml-1"><i class="ph-bold ph-info"></i> Sistem otomatis memproduksi barcode tambahan sebanyak ini untuk stiker.</p>
                                </div>

                                
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Lokasi Rak <span class="text-rose-500">*</span></label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-bookshelf absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                        <input type="text" name="shelf_location" required value="<?php echo e(old('shelf_location')); ?>" placeholder="Misal: Rak A1 / Fiksi 2"
                                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm">
                                    </div>
                                </div>
                                
                                
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Sinopsis / Ringkasan</label>
                                    <textarea name="description" rows="3" placeholder="Tuliskan deskripsi singkat tentang isi buku..."
                                        class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-medium text-elevate-dark transition-all shadow-sm custom-scrollbar text-sm resize-none"><?php echo e(old('description')); ?></textarea>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-elevate-peach-light/40 p-6 rounded-[2rem] border border-elevate-peach/30">
                            <h3 class="text-xs font-black text-elevate-peach-dark uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-white text-elevate-peach-dark flex items-center justify-center text-[10px] shadow-sm border border-elevate-peach/50">3</span>
                                Media & Digital
                            </h3>
                            
                            <div class="space-y-5">
                                
                                <div>
                                    <label class="block text-xs font-bold text-elevate-peach-dark uppercase mb-2 ml-1">Foto Sampul (Maks 5MB)</label>
                                    <div class="relative border-2 border-dashed border-elevate-peach rounded-2xl bg-white hover:bg-elevate-peach-light/50 transition-colors group">
                                        <input type="file" name="cover" id="cover" accept="image/*" onchange="previewCover(event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="p-6 text-center" id="coverUploadArea">
                                            <i class="ph-duotone ph-image text-3xl text-elevate-peach mb-2 group-hover:scale-110 transition-transform"></i>
                                            <p class="text-xs font-bold text-elevate-peach-dark">Klik atau Drag foto kesini</p>
                                        </div>
                                        <div id="coverPreviewArea" class="hidden relative p-2">
                                            <img id="coverImg" src="" class="w-full h-32 object-contain rounded-xl">
                                            <div class="absolute inset-0 flex items-center justify-center bg-elevate-dark/60 rounded-xl opacity-0 hover:opacity-100 transition-opacity">
                                                <span class="text-white text-xs font-bold bg-elevate-dark/80 px-3 py-1 rounded-full"><i class="ph-bold ph-arrows-clockwise"></i> Ganti</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div>
                                    <label class="block text-xs font-bold text-elevate-peach-dark uppercase mb-2 ml-1">File E-Book PDF (Opsional)</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-file-pdf absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-peach-dark transition-colors z-10"></i>
                                        <input type="file" name="ebook_file" accept=".pdf"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-peach focus:ring-4 focus:ring-elevate-peach/20 font-medium text-elevate-dark transition-all shadow-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-elevate-peach/20 file:text-elevate-peach-dark hover:file:bg-elevate-peach/40 cursor-pointer relative z-20">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="<?php echo e(route('library.books.index')); ?>" class="px-6 py-3.5 bg-slate-100 text-elevate-dark/60 font-bold rounded-2xl hover:bg-slate-200 hover:text-elevate-dark transition-colors">Batal</a>
                    <button type="submit" class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 border border-transparent active:scale-95">
                        <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan ke Katalog
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="scannerModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center">
        <div class="absolute inset-0 bg-elevate-dark/80 backdrop-blur-sm" onclick="stopScanner()"></div>
        <div class="bg-white rounded-[2rem] shadow-2xl p-6 w-full max-w-sm relative z-10 animate-fade-in-down border border-slate-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-black text-elevate-dark text-lg">Scan Barcode Buku</h3>
                <button onclick="stopScanner()" class="w-8 h-8 rounded-full bg-elevate-soft text-elevate-dark/60 hover:bg-rose-100 hover:text-rose-600 flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            <div id="reader" class="rounded-2xl overflow-hidden border-4 border-elevate-soft"></div>
            <p class="text-xs text-center text-elevate-dark/60 mt-4 font-medium"><i class="ph-bold ph-info text-elevate-primary"></i> Arahkan kamera ke barcode (ISBN) pada sampul belakang buku.</p>
        </div>
    </div>

    
    <script>
        // --- LOGIKA PREVIEW GAMBAR COVER ---
        function previewCover(event) {
            const input = event.target;
            const previewArea = document.getElementById('coverPreviewArea');
            const uploadArea = document.getElementById('coverUploadArea');
            const img = document.getElementById('coverImg');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    previewArea.classList.remove('hidden');
                    uploadArea.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // --- LOGIKA SCANNER KAMERA ---
        let html5QrcodeScanner = null;

        function startScanner() {
            document.getElementById('scannerModal').classList.remove('hidden');
            if (html5QrcodeScanner === null) {
                html5QrcodeScanner = new Html5Qrcode("reader");
            }
            const config = { fps: 10, qrbox: { width: 250, height: 150 }, aspectRatio: 1.0 };
            html5QrcodeScanner.start({ facingMode: "environment" }, config, (decodedText) => {
                document.getElementById('book_code').value = decodedText;
                // Highlight input untuk indikasi sukses
                document.getElementById('book_code').classList.add('ring-2', 'ring-elevate-accent');
                setTimeout(() => document.getElementById('book_code').classList.remove('ring-2', 'ring-elevate-accent'), 1000);
                
                stopScanner();
            }).catch(err => console.error(err));
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    document.getElementById('scannerModal').classList.add('hidden');
                });
            } else {
                document.getElementById('scannerModal').classList.add('hidden');
            }
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/books/create.blade.php ENDPATH**/ ?>