<?php $__env->startSection('content'); ?>
<div class="container">
    <h1><?php echo e($questionnaire->title); ?></h1>
    <p><?php echo e($questionnaire->description); ?></p>

    <!-- Consent to Participate -->
    <form method="POST" action="<?php echo e(route('questionnaires.storeAnonymousResponse', $questionnaire->id)); ?>">
        <?php echo csrf_field(); ?>

        <?php if(auth()->guest()): ?> 
            <input type="hidden" name="guest_id" value="<?php echo e(uniqid('guest_', true)); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>
                <input type="checkbox" name="consent" value="1" required>
                I consent to participate in this questionnaire.
            </label>
        </div>

        <!-- Questions Section -->
        <?php $__currentLoopData = $questionnaire->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p><strong>Question <?php echo e($index + 1); ?>:</strong> <?php echo e($question->question_text); ?></p>

            <?php if($question->type == 'quantitative'): ?>
                <p>Rate from 1 to 6:</p>
                <div class="rating-options">
                    <?php for($i = 1; $i <= 6; $i++): ?>
                        <label>
                            <input type="radio" name="answers[<?php echo e($question->id); ?>]" value="<?php echo e($i); ?>" required>
                            <?php echo e($i); ?>: <?php echo e($i == 1 ? 'Lowest' : ($i == 6 ? 'Highest' : '')); ?>

                        </label><br>
                    <?php endfor; ?>
                </div>
            <?php else: ?>
                <p>Enter your answer here:</p>
                <textarea name="answers[<?php echo e($question->id); ?>]" class="form-control" rows="3" required></textarea>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit Questionnaire</button>

        <!-- Opt-Out Button -->
        <a href="<?php echo e(route('questionnaires.index')); ?>" class="btn btn-danger ml-3">Opt Out</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/questionnaireApplication/resources/views/questionnaires/show.blade.php ENDPATH**/ ?>