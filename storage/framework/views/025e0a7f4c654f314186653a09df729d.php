<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
</head>
<body>
    <div id="app">
        <!-- Navigation Bar -->
        <nav style="background-color: #333; padding: 10px; display: flex; justify-content: space-between; align-items: center;">
            <!-- Logo -->
            <a href="/" class="navbar-brand" style="flex-shrink: 0;">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" style="width: 120px; height: auto;">
            </a>

            <!-- Login/Register/Logout Links -->
            <div style="display: flex; gap: 10px; margin-right: 10px;">
                <?php if(Route::has('login')): ?>
                    <?php if(auth()->guard()->check()): ?>
                        <!-- Logout option -->
                        <a href="<?php echo e(route('logout')); ?>" 
                           style="color: white; text-decoration: none;"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                            <?php echo csrf_field(); ?>
                        </form>
                    <?php else: ?>
                        <!-- Login and Register options -->
                        <a href="<?php echo e(route('login')); ?>" style="color: white; text-decoration: none;">Login</a>
                        <a href="<?php echo e(route('register')); ?>" style="color: white; text-decoration: none;">Register</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Main Content -->
        <main style="text-align: center; padding: 50px;">
            <h1>Welcome to Researchers. A place to gather data for primary research!</h1>
            <p style="font-size: 18px; color: #555; margin-bottom: 40px;">
                Interested in gathering data for research purposes?
                Interested in participating with questionnaires?
                Click the button below to explore what we have to offer.
            </p>

            <!-- Get Started Button -->
            <a href="<?php echo e(auth()->check() ? route('questionnaires.index') : route('register')); ?>" class="btn btn-primary" style="padding: 10px 20px; font-size: 18px; margin-top: 40px; text-decoration: none; background-color: #007bff; color: white; border-radius: 5px; display: inline-block;">
                Get Started
            </a>

            <!-- View Questionnaires Button -->
            <a href="<?php echo e(route('questionnaires.index')); ?>" class="btn btn-secondary" style="padding: 10px 20px; font-size: 18px; margin-top: 40px; text-decoration: none; background-color: #6c757d; color: white; border-radius: 5px; display: inline-block;">
                View Questionnaires
            </a>

            <!-- Image -->
            <div style="margin-top: 40px; display: flex; justify-content: center; align-items: center;">
                <img src="<?php echo e(asset('images/Research.png')); ?>" alt="Research Image" style="width: 300px; height: auto;">
            </div>
        </main>
    </div>
</body>
</html>
<?php /**PATH /var/www/html/questionnaireApplication/resources/views/dashboard.blade.php ENDPATH**/ ?>