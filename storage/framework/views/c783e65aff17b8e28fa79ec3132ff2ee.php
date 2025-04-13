<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Manage My Questionnaires</h1>

    <!-- Button to navigate back to published questionnaires -->
    <a href="<?php echo e(route('questionnaires.index')); ?>" class="btn btn-info mb-3">Back to Published Questionnaires</a>

    <!-- Create Questionnaire Button -->
    <a href="<?php echo e(route('create')); ?>" class="btn btn-primary mb-3">Create New Questionnaire</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $questionnaires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $questionnaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($questionnaire->title); ?></td>
                <td><?php echo e($questionnaire->description); ?></td>
                <td>
                    <?php if($questionnaire->published): ?>
                        <span class="badge bg-success">Published</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Not Published</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($questionnaire->published): ?>
                        <!-- Only show the Retrieve Responses button if it's published -->
                        <a href="<?php echo e(route('questionnaires.retrieveResponses', $questionnaire->id)); ?>" class="btn btn-primary btn-sm">Retrieve Responses</a>
                        
                        <!-- Export Responses link when responses are retrieved -->
                        <a href="<?php echo e(route('questionnaires.exportResponses', $questionnaire->id)); ?>" class="btn btn-success btn-sm">Export Responses to CSV</a>
                    <?php else: ?>
                        <!-- Show edit and delete if not published -->
                        <a href="<?php echo e(route('questionnaires.edit', $questionnaire->id)); ?>" class="btn btn-info btn-sm">Edit</a>

                        <form action="<?php echo e(route('questionnaires.destroy', $questionnaire->id)); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/questionnaireApplication/resources/views/manage.blade.php ENDPATH**/ ?>