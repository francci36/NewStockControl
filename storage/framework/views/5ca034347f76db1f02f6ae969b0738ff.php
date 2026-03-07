

<?php $__env->startSection('title', 'Créer une sous-catégorie'); ?>
<?php $__env->startSection('page-title', 'Nouvelle sous-catégorie'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">
                <i class="fas fa-plus-circle mr-2"></i>
                Créer une nouvelle sous-catégorie
            </h3>
        </div>
        
        <form action="<?php echo e(route('vendor.categories.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
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

                <!-- Catégorie principale -->
                <div class="form-group mb-3">
                    <label for="main_category" class="form-label fw-bold">
                        Catégorie principale <span class="text-danger">*</span>
                    </label>
                    <select name="main_category" id="main_category" class="form-control" required>
                        <option value="">Sélectionnez une catégorie</option>
                        <?php $__currentLoopData = $mainCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('main_category') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->translated_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Nom de la sous-catégorie -->
                <div class="form-group mb-3">
                    <label for="name" class="form-label fw-bold">
                        Nom de la sous-catégorie <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('name')); ?>"
                           required
                           placeholder="Ex: Vins rouges">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Ce nom sera automatiquement traduit selon la langue du visiteur
                    </small>
                    <?php $__errorArgs = ['name'];
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

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('vendor.categories.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> Créer la sous-catégorie
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('vendor.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\franc\NewStockControl\resources\views/vendor/categories/create.blade.php ENDPATH**/ ?>