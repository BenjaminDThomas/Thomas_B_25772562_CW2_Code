<?php $__env->startSection('content'); ?>
<div class="container">
    <h1><?php echo e($questionnaire->title); ?> - Responses</h1>

    <!-- Navigation Buttons -->
    <div class="mb-3">
        <a href="<?php echo e(route('questionnaires.index')); ?>" class="btn btn-secondary">Back to Questionnaires</a>
        <a href="<?php echo e(route('questionnaires.exportResponses', $questionnaire->id)); ?>" class="btn btn-primary">Export to CSV</a>
    </div>

    <?php if($responses->isNotEmpty()): ?>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Response ID</th>
                    <th>User</th>
                    <?php $__currentLoopData = $questionnaire->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th><?php echo e($question->question_text); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $responses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $response): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($response->id); ?></td>
                        <td><?php echo e($response->user_id ? 'User ' . $response->user_id : 'Anonymous'); ?></td>

                        <?php $__currentLoopData = $questionnaire->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td>
                                <?php
                                    $userAnswer = $response->answers->firstWhere('question_id', $question->id);
                                ?>
                                <?php echo e($userAnswer ? nl2br(e($userAnswer->answer)) : 'No answer'); ?>

                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

        </table>

        <div class="d-flex justify-content-center">
            <?php echo e($responses->links()); ?>

        </div>
    <?php else: ?>
        <p class="alert alert-info">No responses found for this questionnaire.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/questionnaireApplication/resources/views/exports/responses.blade.php ENDPATH**/ ?>