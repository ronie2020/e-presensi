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
                        <h3 class="text-sm font-bold text-rose-700">Gagal Menyimpan Perubahan</h3>
                        <ul class="list-disc list-inside text-xs text-rose-600 mt-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                <div class="bg-elevate-gradient-main p-8 text-elevate-dark relative overflow-hidden border-b border-white/60">
                    <div class="absolute -right-6 -top-6 text-white/40 text-9xl pointer-events-none mix-blend-overlay">
                        <i class="ph-fill ph-pencil-circle"></i>
                    </div>
                    <h2 class="text-3xl font-black relative z-10">Edit Data Buku</h2>
                    <p class="text-elevate-dark/80 text-sm font-semibold relative z-10 mt-2">Perbarui informasi detail buku dan inventaris.</p>
                </div>

                <div class="p-8">
                    <form action="<?php echo e(route('library.books.update', $book->id)); ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            <div class="space-y-6">
                                <div class="bg-elevate-soft p-6 rounded-[2rem] border border-slate-200">
                                    <h3 class="text-xs font-black text-elevate-dark uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-white text-elevate-primary flex items-center justify-center text-[10px] shadow-sm">1</span>
                                        Identitas Buku
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Kode Buku / Barcode Induk</label>
                                            <div class="flex gap-2">
                                                <div class="relative flex-1 group">
                                                    <i class="ph-bold ph-barcode absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                                    <input type="text" name="book_code" id="book_code" value="<?php echo e(old('book_code', $book->book_code)); ?>" readonly
                                                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 cursor-not-allowed font-mono font-bold text-slate-500 shadow-sm" title="Kode buku tidak dapat diubah setelah dibuat">
                                                </div>
                                            </div>
                                            <p class="text-[10px] text-elevate-dark/50 mt-2 ml-1"><i class="ph-bold ph-info text-elevate-primary"></i> Kode induk tidak dapat diubah untuk menjaga sinkronisasi eksemplar.</p>
                                        </div>

                                        
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Judul Buku</label>
                                            <input type="text" name="title" value="<?php echo e(old('title', $book->title)); ?>" required 
                                                class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm">
                                        </div>

                                      
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Kategori</label>
                                            <div class="flex gap-2">
                                                <div class="relative flex-1">
                                                    <select name="category_id" id="category_id" class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer">
                                                        <option value="">-- Pilih Kategori --</option>
                                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($cat->id); ?>" <?php echo e((old('category_id', $book->category_id) == $cat->id) ? 'selected' : ''); ?>>
                                                                <?php echo e($cat->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                                </div>
                                                <button type="button" onclick="addNewCategory()" class="shrink-0 w-12 bg-white text-elevate-primary font-black rounded-2xl hover:bg-elevate-primary hover:text-white transition-all border border-slate-200 hover:border-elevate-primary shadow-sm">
                                                    <i class="ph-bold ph-plus text-lg"></i>
                                                </button>
                                            </div>
                                        </div>

                                        
                                       <div class="grid grid-cols-2 gap-4 mt-2">
                                            <div>
                                                <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Stok Saat Ini</label>
                                                <div class="relative">
                                                    <i class="ph-bold ph-stack absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                                    <input type="number" value="<?php echo e($book->stock); ?>" readonly 
                                                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 cursor-not-allowed font-bold text-slate-500 shadow-sm" title="Total stok tidak bisa diubah manual">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">+ Tambah Eksemplar</label>
                                                <div class="relative group">
                                                    <i class="ph-bold ph-plus-circle absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                    <input type="number" name="tambah_eksemplar" value="0" min="0" max="500"
                                                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-primary transition-all shadow-sm" placeholder="Misal: 5">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-elevate-dark/50 mt-1 ml-1 leading-relaxed"><i class="ph-bold ph-info text-elevate-primary"></i> Isi angka pada kolom <b>Tambah Eksemplar</b> untuk men-generate barcode buku lama atau penambahan fisik baru.</p>

                                        
                                        <div class="grid grid-cols-2 gap-4 mt-2">
                                            <div>
                                                <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Tahun Terbit</label>
                                                <input type="number" name="year" value="<?php echo e(old('year', $book->year)); ?>" placeholder="YYYY" min="1900" max="<?php echo e(date('Y') + 1); ?>"
                                                    class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Tanggal Pembelian</label>
                                                <div class="relative group">
                                                    <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                    <input type="date" name="purchase_date" value="<?php echo e(old('purchase_date', $book->purchase_date)); ?>"
                                                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm uppercase">
                                                </div>
                                            </div>
                                        </div>
                                        
                                         
                                        <div>
                                            <label class="flex items-center gap-3 p-4 border border-slate-200 bg-white rounded-2xl cursor-pointer hover:border-elevate-accent transition shadow-sm mt-4">
                                                <input type="checkbox" name="is_textbook" value="1" <?php echo e(old('is_textbook', $book->is_textbook) ? 'checked' : ''); ?> class="w-5 h-5 text-elevate-primary border-slate-300 rounded focus:ring-elevate-accent">
                                                <div>
                                                    <span class="block text-sm font-bold text-elevate-dark">Ini Buku Paket / Pelajaran</span>
                                                    <span class="block text-xs text-elevate-dark/60 mt-0.5">Buku paket bisa dipinjam massal selama 1 tahun.</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="bg-elevate-soft p-6 rounded-[2rem] border border-slate-200">
                                    <h3 class="text-xs font-black text-elevate-dark uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-white text-elevate-primary flex items-center justify-center text-[10px] shadow-sm">2</span>
                                        Data Pustaka & Media
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Pengarang</label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-pen-nib absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                <input type="text" name="author" value="<?php echo e(old('author', $book->author)); ?>" placeholder="Nama Penulis" 
                                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Penerbit</label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                <input type="text" name="publisher" value="<?php echo e(old('publisher', $book->publisher)); ?>" placeholder="Nama Penerbit" 
                                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Lokasi Rak</label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-squares-four absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                <input type="text" name="shelf_location" value="<?php echo e(old('shelf_location', $book->shelf_location)); ?>" placeholder="Contoh: R-01" 
                                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark transition-all shadow-sm">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Cover Buku</label>
                                            <?php if($book->cover_path): ?>
                                                <div class="flex items-center gap-4 mb-3 p-3 bg-white rounded-xl border border-slate-100 shadow-sm" id="currentCoverContainer">
                                                    <div class="w-12 h-16 rounded overflow-hidden bg-slate-100 flex-shrink-0">
                                                        <img src="<?php echo e(asset('storage/' . $book->cover_path)); ?>" class="w-full h-full object-cover">
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-elevate-dark">Cover Saat Ini</p>
                                                        <p class="text-[10px] text-elevate-dark/60">Biarkan kosong jika tidak ingin mengubah.</p>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="relative group">
                                                <input type="file" name="cover" id="coverInput" onchange="previewCover(event)" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-primary/10 border border-dashed border-slate-300 rounded-2xl py-3 px-4 hover:border-elevate-primary transition-all cursor-pointer bg-white shadow-sm"/>
                                            </div>
                                            <img id="coverPreview" class="hidden mt-3 rounded-xl border border-slate-200 object-cover" style="max-height: 160px;" alt="Cover Preview" />
                                        </div>

                                        
                                        <div class="pt-4 border-t border-slate-200">
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Update File E-Book</label>
                                            
                                            <?php if($book->ebook_path): ?>
                                                <div class="flex items-center gap-2 mb-3 px-3 py-2 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100">
                                                    <i class="ph-fill ph-check-circle text-lg"></i>
                                                    <div>
                                                        <p class="text-xs font-bold">E-Book sudah tersedia</p>
                                                        <p class="text-[10px] opacity-80">Upload baru untuk mengganti.</p>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="relative group">
                                                <i class="ph-bold ph-file-pdf absolute left-4 top-1/2 -translate-y-1/2 text-rose-400 z-10"></i>
                                                <input type="file" name="ebook_file" accept="application/pdf" class="block w-full text-xs text-slate-500 pl-11 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100 border border-dashed border-slate-300 rounded-2xl py-3 px-4 hover:border-rose-400 transition-all cursor-pointer bg-white shadow-sm <?php $__errorArgs = ['ebook_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-500 bg-rose-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"/>
                                            </div>
                                            <?php $__errorArgs = ['ebook_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="mt-2 p-3 bg-rose-100 border border-rose-200 rounded-xl text-rose-700 text-xs font-bold flex items-center gap-2">
                                                    <i class="ph-bold ph-warning-circle text-lg"></i>
                                                    <?php echo e($message); ?>

                                                </div>
                                            <?php else: ?>
                                                <p class="text-[10px] text-elevate-dark/50 mt-1 ml-1 font-medium">*Hanya file PDF, Maksimal 50MB.</p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="border-t border-slate-100 pt-6">
                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Sinopsis</label>
                            <textarea name="description" rows="3" placeholder="Ringkasan cerita..." class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-medium text-elevate-dark transition-all shadow-sm"><?php echo e(old('description', $book->description)); ?></textarea>
                        </div>

                        
                       <div class="pt-4 flex justify-end gap-4 border-t border-slate-100">
                            <a href="<?php echo e(route('library.books.index')); ?>" class="px-6 py-3.5 rounded-2xl text-elevate-dark/60 font-bold text-sm hover:bg-slate-100 hover:text-elevate-dark transition-colors border border-transparent">Batal</a>
                            <button type="submit" class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 active:scale-95 border border-transparent">
                                <i class="ph-bold ph-check-circle text-lg"></i>
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

     
    <div id="scannerModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-elevate-dark/80 backdrop-blur-sm" onclick="stopScanner()"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden relative z-10 p-6 border border-slate-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-black text-elevate-dark">Scan Barcode Buku</h3>
                <button onclick="stopScanner()" class="text-slate-400 hover:text-rose-500 transition-colors w-8 h-8 rounded-full hover:bg-rose-50 flex items-center justify-center">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>
            <div id="reader" class="w-full rounded-2xl overflow-hidden bg-slate-100 border-4 border-elevate-soft"></div>
            <p class="text-xs text-elevate-dark/60 text-center mt-4 font-medium"><i class="ph-bold ph-info text-elevate-primary"></i> Arahkan kamera ke barcode buku.</p>
        </div>
    </div>

    
    <script>
        function previewCover(event) {
            const input = event.target;
            const preview = document.getElementById('coverPreview');
            const currentCover = document.getElementById('currentCoverContainer');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if(currentCover) currentCover.classList.add('hidden'); 
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                preview.classList.add('hidden');
                if(currentCover) currentCover.classList.remove('hidden'); 
            }
        }
        
        async function addNewCategory() {
             const { value: newCategory } = await Swal.fire({
                title: 'Tambah Kategori Baru',
                input: 'text',
                inputPlaceholder: 'Contoh: Novel, Biografi, Sains',
                confirmButtonText: 'Simpan',
                confirmButtonColor: '#2c3f61', // elevate-dark
                showCancelButton: true,
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-bold',
                    input: 'rounded-xl border-slate-200 focus:ring-elevate-accent focus:border-elevate-accent'
                },
                inputValidator: (value) => { if (!value) return 'Nama kategori tidak boleh kosong!' }
            });

            if (newCategory) {
                try {
                    const response = await fetch("<?php echo e(route('library.books.categories.ajax')); ?>", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" },
                        body: JSON.stringify({ name: newCategory })
                    });
                    const data = await response.json();
                    if (data.success) {
                        const select = document.getElementById('category_id');
                        const option = new Option(data.name, data.id, true, true);
                        select.add(option);
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-[2rem]' } });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, customClass: { popup: 'rounded-[2rem]' } });
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server.', customClass: { popup: 'rounded-[2rem]' } });
                }
            }
        }

        let html5QrcodeScanner = null;
        function startScanner() {
            document.getElementById('scannerModal').classList.remove('hidden');
            if (html5QrcodeScanner === null) {
                html5QrcodeScanner = new Html5Qrcode("reader");
            }
            html5QrcodeScanner.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 150 }, aspectRatio: 1.0 }, (decodedText) => {
                document.getElementById('book_code').value = decodedText;
                stopScanner();
            }).catch(err => console.error(err));
        }
        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => { document.getElementById('scannerModal').classList.add('hidden'); });
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/books/edit.blade.php ENDPATH**/ ?>