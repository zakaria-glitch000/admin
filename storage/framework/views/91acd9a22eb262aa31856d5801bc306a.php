<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title><?php echo $__env->yieldContent('title'); ?> | PC MAROC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Skote CSS & Icons (Fix: 'build/' au lieu de 'assets/') -->
    <link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />
    <?php echo $__env->yieldPushContent('css'); ?>
</head>

<body data-sidebar="dark">
    <div id="layout-wrapper">
        <?php echo $__env->make('layouts.topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <?php echo $__env->make('partials.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
            
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6"><script>document.write(new Date().getFullYear())</script> © PC MAROC .</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Skote JS Core (Fix: 'build/' au lieu de 'assets/') -->
    <script src="<?php echo e(asset('build/libs/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/metismenu/metisMenu.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/simplebar/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/js/app.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\zakar\Desktop\Admin\resources\views/layouts/master.blade.php ENDPATH**/ ?>