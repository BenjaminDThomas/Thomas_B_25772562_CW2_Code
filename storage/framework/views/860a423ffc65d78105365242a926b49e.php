<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>
    <div id="app">
        <nav style="background-color: #333; padding: 10px; display: flex; justify-content: space-between; align-items: center;">
            <!-- Logo -->
            <a href="<?php echo e(url('/')); ?>" class="navbar-brand">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" style="width: 120px; height: auto;">
            </a>
            
            <div style="display: flex; gap: 10px; margin-right: 10px; align-items: center;">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(url('/questionnaires')); ?>" style="color: white; text-decoration: none;">Home</a>
                    
                    <!-- User Name -->
                    <span style="color: white;"><?php echo e(Auth::user()->name); ?></span>
                    
                    <!-- Logout Button -->
                    <form action="<?php echo e(route('logout')); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px;">
                            Logout
                        </button>
                    </form>
                <?php else: ?>
                    <?php if(Route::has('login')): ?>
                        <a href="<?php echo e(route('login')); ?>" style="color: white; text-decoration: none;">Login</a>
                    <?php endif; ?>
                    <?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" style="color: white; text-decoration: none;">Register</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </nav>

        <main class="py-4">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</body>
</html>
<?php /**PATH /var/www/html/questionnaireApplication/resources/views/layouts/app.blade.php ENDPATH**/ ?>