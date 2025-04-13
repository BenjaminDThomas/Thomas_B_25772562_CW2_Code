<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>All Published Questionnaires</h1>


    <a href="<?php echo e(route('questionnaires.index')); ?>" class="btn btn-info mb-3">View All Questionnaires</a>
    <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('manage')); ?>" class="btn btn-secondary mb-3">Manage Your Questionnaires</a>
        <a href="<?php echo e(route('create')); ?>" class="btn btn-primary mb-3">Create New Questionnaire</a>
    <?php endif; ?>

    <?php if($questionnaires->isEmpty()): ?>
        <p class="text-muted">No published questionnaires available.</p>
    <?php else: ?>
        <div class="mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $questionnaires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $questionnaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($questionnaire->title); ?></td>
                        <td><?php echo e($questionnaire->description); ?></td>
                        <td>
                            <a href="<?php echo e(route('questionnaires.show', $questionnaire->id)); ?>" class="btn btn-primary btn-sm">Participate</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/questionnaireApplication/resources/views/questionnaires/index.blade.php ENDPATH**/ ?>