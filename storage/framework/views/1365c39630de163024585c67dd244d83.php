

<?php $__env->startSection('title', 'Modifier la catégorie'); ?>
<?php $__env->startSection('page-title', 'Modifier la catégorie'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="<?php echo e(route('vendor.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('vendor.categories.index')); ?>">Catégories</a></li>
    <li class="breadcrumb-item active">Modifier</li>
</ol>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-warning">
            <h3 class="card-title">
                <i class="fas fa-edit mr-2"></i>
                Modifier : 
                <strong>
                    <?php
                        $currentLocale = app()->getLocale();
                        echo is_array($category->name) 
                            ? ($category->name[$currentLocale] ?? $category->name['fr'] ?? '')
                            : $category->name;
                    ?>
                </strong>
            </h3>
        </div>
        
        <form action="<?php echo e(route('vendor.categories.update', $category)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="card-body">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Erreurs de validation :</strong>
                        <ul class="mb-0 mt-2">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="name_fr" class="form-label fw-bold">
                        Nom (Français) <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name_fr" 
                           id="name_fr" 
                           class="form-control <?php $__errorArgs = ['name_fr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('name_fr', is_array($category->name) ? ($category->name['fr'] ?? '') : $category->name)); ?>"
                           required>
                    <?php $__errorArgs = ['name_fr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="name_en" class="form-label fw-bold">
                        Nom (Anglais)
                    </label>
                    <input type="text" 
                           name="name_en" 
                           id="name_en" 
                           class="form-control <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('name_en', is_array($category->name) ? ($category->name['en'] ?? '') : '')); ?>">
                    <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="slug" class="form-label fw-bold">Slug (URL)</label>
                            <input type="text" 
                                   name="slug" 
                                   id="slug" 
                                   class="form-control <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('slug', $category->slug)); ?>"
                                   readonly
                                   style="background-color: #f5f5f5;">
                            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted">Le slug ne peut pas être modifié après création</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       class="custom-control-input"
                                       value="1"
                                       <?php echo e(old('is_active', $category->is_active) ? 'checked' : ''); ?>>
                                <label class="custom-control-label" for="is_active">
                                    <i class="fas fa-check-circle text-success mr-1"></i>
                                    Catégorie active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                    $productsCount = $category->products_count ?? $category->products()->count();
                ?>
                <?php if($productsCount > 0): ?>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong><?php echo e($productsCount); ?> produit(s)</strong> sont associés à cette catégorie.
                </div>
                <?php endif; ?>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('vendor.categories.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-2"></i> Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('name_fr').addEventListener('keyup', function() {
    // Prévisualisation du slug (mais ne le modifie pas car il est en lecture seule)
    let slugPreview = document.createElement('small');
    slugPreview.className = 'text-muted d-block mt-1';
    slugPreview.innerHTML = 'Slug généré: ' + this.value.toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '');
    
    let existingPreview = document.getElementById('slug-preview');
    if (existingPreview) existingPreview.remove();
    
    slugPreview.id = 'slug-preview';
    document.getElementById('slug').parentNode.appendChild(slugPreview);
});
</script>

<style>
.bg-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
    color: white;
}
.form-label {
    font-weight: 600;
    color: #495057;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('vendor.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\franc\NewStockControl\resources\views/vendor/categories/edit.blade.php ENDPATH**/ ?>