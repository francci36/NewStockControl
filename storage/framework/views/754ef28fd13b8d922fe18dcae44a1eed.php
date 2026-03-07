


<?php $__env->startSection('title', 'Modifier le produit'); ?>

<?php $__env->startSection('page-title', 'Modifier le produit'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg border-0">
        <!-- En-tête responsive -->
        <div class="card-header bg-gradient-warning text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
            <h5 class="mb-0">
                <i class="fas fa-edit mr-2"></i> Modifier le produit : <strong><?php echo e($product->translated_name ?? 'Produit'); ?></strong>
            </h5>
            <div>
                <span class="badge bg-light text-dark mr-2">SKU: <?php echo e($product->sku ?? 'Non défini'); ?></span>
                <a href="<?php echo e(route('vendor.products.index')); ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i> <span class="d-none d-sm-inline">Retour</span>
                    <span class="d-sm-none">←</span>
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> <?php echo e(session('success')); ?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?php echo e(session('error')); ?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Erreurs de validation :</strong>
                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('vendor.products.update', $product)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="row">
                    <!-- Colonne principale (gauche) -->
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <!-- Nom du produit (français) -->
                        <div class="form-group mb-3">
                            <label for="name_fr" class="form-label fw-bold">
                                Nom du produit (français) <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name_fr" 
                                   id="name_fr" 
                                   class="form-control form-control-lg <?php $__errorArgs = ['name_fr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('name_fr', is_array($product->name) ? ($product->name['fr'] ?? '') : $product->name)); ?>"
                                   required>
                            <small class="text-muted">Le nom en français sera utilisé pour l'administration</small>
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

                        <!-- Nom du produit (anglais) -->
                        <div class="form-group mb-3">
                            <label for="name_en" class="form-label fw-bold">
                                Nom du produit (anglais)
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
                                   value="<?php echo e(old('name_en', is_array($product->name) ? ($product->name['en'] ?? '') : '')); ?>">
                            <small class="text-muted">Nom du produit en anglais pour les clients internationaux</small>
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

                        <!-- Description courte (français) -->
                        <div class="form-group mb-3">
                            <label for="short_description_fr" class="form-label fw-bold">Description courte (français)</label>
                            <input type="text" 
                                   name="short_description_fr" 
                                   id="short_description_fr" 
                                   class="form-control <?php $__errorArgs = ['short_description_fr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('short_description_fr', is_array($product->short_description) ? ($product->short_description['fr'] ?? '') : $product->short_description)); ?>"
                                   maxlength="500"
                                   placeholder="Brève description en français (max 500 caractères)">
                            <?php $__errorArgs = ['short_description_fr'];
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

                        <!-- Description courte (anglais) -->
                        <div class="form-group mb-3">
                            <label for="short_description_en" class="form-label fw-bold">Description courte (anglais)</label>
                            <input type="text" 
                                   name="short_description_en" 
                                   id="short_description_en" 
                                   class="form-control <?php $__errorArgs = ['short_description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('short_description_en', is_array($product->short_description) ? ($product->short_description['en'] ?? '') : '')); ?>"
                                   maxlength="500"
                                   placeholder="Brief description in English (max 500 characters)">
                            <?php $__errorArgs = ['short_description_en'];
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

                        <!-- Description détaillée (français) -->
                        <div class="form-group mb-3">
                            <label for="description_fr" class="form-label fw-bold">Description détaillée (français)</label>
                            <textarea name="description_fr" 
                                      id="description_fr" 
                                      rows="6" 
                                      class="form-control <?php $__errorArgs = ['description_fr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      placeholder="Description détaillée en français..."><?php echo e(old('description_fr', is_array($product->description) ? ($product->description['fr'] ?? '') : $product->description)); ?></textarea>
                            <?php $__errorArgs = ['description_fr'];
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

                        <!-- Description détaillée (anglais) -->
                        <div class="form-group mb-3">
                            <label for="description_en" class="form-label fw-bold">Description détaillée (anglais)</label>
                            <textarea name="description_en" 
                                      id="description_en" 
                                      rows="6" 
                                      class="form-control <?php $__errorArgs = ['description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      placeholder="Detailed description in English..."><?php echo e(old('description_en', is_array($product->description) ? ($product->description['en'] ?? '') : '')); ?></textarea>
                            <?php $__errorArgs = ['description_en'];
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
                    </div>

                    <!-- Colonne latérale (droite) -->
                    <div class="col-lg-4">
                        <!-- Catégorie -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Catégorie <span class="text-danger">*</span></h6>
                            </div>
                            <div class="card-body">
                                <select name="category_id" 
                                        id="category_id" 
                                        class="form-control <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>" 
                                            <?php echo e(old('category_id', $product->category_id) == $category->id ? 'selected' : ''); ?>>
                                            <?php echo e($category->translated_name ?? $category->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['category_id'];
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
                        </div>

                        <!-- Prix et Stock -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Prix et Stock</h6>
                            </div>
                            <div class="card-body">
                                <!-- Prix -->
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label fw-bold">
                                        Prix (€) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">€</span>
                                        </div>
                                        <input type="number" 
                                               name="price" 
                                               id="price" 
                                               step="0.01" 
                                               min="0"
                                               class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               value="<?php echo e(old('price', $product->price)); ?>"
                                               required>
                                    </div>
                                    <?php $__errorArgs = ['price'];
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

                                <!-- Quantité -->
                                <div class="form-group mb-3">
                                    <label for="quantity" class="form-label fw-bold">
                                        Quantité en stock <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           name="quantity" 
                                           id="quantity" 
                                           min="0"
                                           class="form-control <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('quantity', $product->quantity)); ?>"
                                           required>
                                    <?php $__errorArgs = ['quantity'];
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

                                <!-- Seuil d'alerte -->
                                <div class="form-group mb-3">
                                    <label for="stock_threshold" class="form-label fw-bold">Seuil d'alerte</label>
                                    <input type="number" 
                                           name="stock_threshold" 
                                           id="stock_threshold" 
                                           min="1"
                                           class="form-control <?php $__errorArgs = ['stock_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('stock_threshold', $product->stock_threshold ?? 5)); ?>">
                                    <?php $__errorArgs = ['stock_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="text-muted d-block mt-1">Stock minimum avant alerte</small>
                                </div>

                                <!-- Statut actif -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               name="is_active" 
                                               id="is_active" 
                                               value="1"
                                               class="custom-control-input"
                                               <?php echo e(old('is_active', $product->is_active) ? 'checked' : ''); ?>>
                                        <label class="custom-control-label" for="is_active">
                                            <i class="fas fa-check-circle text-success mr-1"></i> Produit actif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Image du produit</h6>
                            </div>
                            <div class="card-body">
                                <?php if($product->image): ?>
                                    <div class="text-center mb-3">
                                        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" 
                                             alt="<?php echo e($product->translated_name ?? 'Produit'); ?>" 
                                             class="img-fluid rounded border" 
                                             style="max-height: 150px;">
                                        <p class="text-muted small mt-1">Image actuelle</p>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <label for="image" class="form-label fw-bold">Nouvelle image</label>
                                    <div class="custom-file">
                                        <input type="file" 
                                               name="image" 
                                               id="image" 
                                               class="custom-file-input <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               accept="image/jpeg,image/png,image/jpg,image/gif">
                                        <label class="custom-file-label" for="image">
                                            <i class="fas fa-upload mr-2"></i> Choisir un fichier
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Formats: JPG, PNG, GIF. Max: 2Mo. Laissez vide pour conserver l'image actuelle.
                                    </small>
                                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <!-- Aperçu de la nouvelle image -->
                                <div id="imagePreview" class="text-center mt-3" style="display: none;">
                                    <img src="#" alt="Aperçu" class="img-fluid rounded border" style="max-height: 150px;">
                                    <p class="text-muted small mt-1">Nouvelle image</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Boutons d'action responsives -->
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <a href="<?php echo e(route('vendor.products.index')); ?>" class="btn btn-secondary w-100 w-sm-auto">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning w-100 w-sm-auto">
                        <i class="fas fa-save mr-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

/* ===== STYLES POUR LE FORMULAIRE ===== */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
}

.form-control-lg {
    border-radius: 0.5rem;
}

.input-group-text {
    background: #f8f9fa;
    border: 1px solid #ced4da;
}

/* ===== STYLES POUR LE CUSTOM FILE ===== */
.custom-file-label {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    border-radius: 0.5rem;
}
.custom-file-label::after {
    content: "Parcourir";
    background: #667eea;
    color: white;
    border-left: 1px solid #5a6fd8;
    border-radius: 0 0.5rem 0.5rem 0;
}
.custom-file-input:focus ~ .custom-file-label {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
}

/* ===== MODE SOMBRE ===== */
.dark .card {
    background: #2d2d44;
    border-color: #4a4a6a;
}
.dark .card-header.bg-light {
    background: #3a3a5a !important;
    border-bottom-color: #4a4a6a;
}
.dark .form-label {
    color: #c0c0e0;
}
.dark .form-control {
    background: #2d2d44;
    border-color: #4a4a6a;
    color: #e0e0e0;
}
.dark .form-control:focus {
    background: #35354a;
    border-color: #6a6a9a;
    color: #ffffff;
}
.dark .input-group-text {
    background: #35354a;
    border-color: #4a4a6a;
    color: #c0c0e0;
}
.dark .custom-file-label {
    background: #2d2d44;
    border-color: #4a4a6a;
    color: #c0c0e0;
}
.dark .custom-file-label::after {
    background: #5a6fd8;
    color: white;
    border-left-color: #4a4a6a;
}
.dark .text-muted {
    color: #a0a0c0 !important;
}

/* ===== STYLES RESPONSIVES ===== */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .form-control-lg {
        font-size: 1rem;
    }
    
    .btn {
        padding: 0.6rem 1rem;
    }
    
    .custom-file-label {
        font-size: 0.9rem;
    }
    
    .card-header h5 {
        font-size: 1.1rem;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 0.8rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .w-sm-auto {
        width: 100%;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
    }
}

/* Classes utilitaires */
.w-sm-auto { width: auto; }
@media (min-width: 576px) {
    .w-sm-auto { width: auto; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = imagePreview ? imagePreview.querySelector('img') : null;

    // Aperçu de l'image
    if (imageInput && imagePreview && previewImg) {
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreview.style.display = 'none';
                previewImg.src = '#';
            }
        });
    }

    // Mise à jour du label du fichier
    if (imageInput) {
        const fileLabel = document.querySelector('.custom-file-label');
        if (fileLabel) {
            imageInput.addEventListener('change', function() {
                let fileName = this.files.length ? this.files[0].name : 'Choisir un fichier';
                fileLabel.innerHTML = '<i class="fas fa-upload mr-2"></i> ' + fileName;
            });
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('vendor.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\franc\NewStockControl\resources\views/vendor/products/edit.blade.php ENDPATH**/ ?>