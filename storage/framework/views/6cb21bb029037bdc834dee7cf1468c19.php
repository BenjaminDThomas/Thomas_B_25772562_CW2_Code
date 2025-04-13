<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Create New Questionnaire</h1>

    <form action="<?php echo e(route('questionnaires.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" class="form-control" value="<?php echo e(old('title')); ?>" required>
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control"><?php echo e(old('description')); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="alert alert-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <input type="checkbox" id="published" name="published" value="1" <?php echo e(old('published') ? 'checked' : ''); ?>>
            <label for="published">Publish this questionnaire</label>
        </div>

        <div id="questions-container">
            <div class="question-group" id="question-0">
                <h4>Question 1</h4>

                <div class="form-group">
                    <label for="question-text-0">Question Text:</label>
                    <input type="text" id="question-text-0" name="questions[0][text]" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="question-type-0">Question Type:</label>
                    <select id="question-type-0" name="questions[0][type]" class="form-control" required>
                        <option value="quantitative">Quantitative (Rating 1-6)</option>
                        <option value="qualitative">Qualitative (Text Answer)</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="button" id="add-question" class="btn btn-primary">Add Question</button>
        <button type="submit" class="btn btn-success">Create Questionnaire</button>
    </form>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/questionnaireApplication/resources/views/create.blade.php ENDPATH**/ ?>