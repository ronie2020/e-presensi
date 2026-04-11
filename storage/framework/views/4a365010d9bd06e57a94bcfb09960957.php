<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['active']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
// Style Aktif: Background Putih, Teks Biru, Shadow, Sedikit lebih besar (scale)
// Style Inaktif: Transparan, Teks Biru Muda, Hover jadi agak terang
$classes = ($active ?? false)
            ? 'flex items-center px-5 py-3.5 bg-white text-blue-700 rounded-2xl shadow-lg shadow-blue-900/20 transition-all duration-300 font-bold transform scale-[1.02] relative overflow-hidden group'
            : 'flex items-center px-5 py-3.5 text-blue-100/80 hover:text-white hover:bg-white/10 rounded-2xl transition-all duration-200 font-medium group';

$iconClasses = ($active ?? false)
            ? 'text-blue-600 mr-4 transition-colors duration-300'
            : 'text-blue-300/70 group-hover:text-white mr-4 transition-colors duration-300';
?>

<a <?php echo e($attributes->merge(['class' => $classes . ' mb-2'])); ?>>
    
    <?php echo e($slot); ?>

    
    
    <?php if($active ?? false): ?>
        <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-blue-500"></span>
    <?php endif; ?>
</a><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\components\nav-link-vertical.blade.php ENDPATH**/ ?>