<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class ZipProject extends Command
{
    protected $signature = 'project:zip';
    protected $description = 'Zip the Laravel project excluding node_modules, vendor, and test junk';

    public function handle()
    {
        $rootPath = base_path();
        $zipPath = base_path('questionnaire_clean.zip');

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error('Could not create zip file.');
            return 1;
        }

        $exclude = [
            'node_modules',
            'vendor',
            '.git',
            'storage/logs',
            'storage/framework',
            'tests',
        ];

        $testLikePatterns = [
            '-', '[description]', '[text]', '[title]', '[type]',
            'A_test_questionnaire.', 'Blue', 'email', 'Export_Test',
            'How_old_are_you_', 'id id', 'nullable_array', 'nullable_boolean',
            'password123', 'required_array', 'required_i_', 'quantitative',
            'qualitative', 'required_string_max_255', 'required_string',
            'responses', 'Should_export_responses', 'test_csrf_token',
            'Test_Questionnaire', 'Test_User', 'testuser@example',
            'Updated_Description', 'Updated_Title', 'What_is_your_favorite_color_',
            'What_is_your_name_', 'zibkMnTt', 'ziDvHJsL', 'ziMG4U2y',
            'ziR7J5CV', 'ziRRFEMF'
        ];

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isFile()) continue;

            $filePath = $file->getRealPath();
            $relativePath = str_replace($rootPath . DIRECTORY_SEPARATOR, '', $filePath);

            // Exclude directories and testy things
            if (
                collect($exclude)->contains(fn($ex) => str_contains($relativePath, $ex)) ||
                collect($testLikePatterns)->contains(fn($p) => str_contains($relativePath, $p))
            ) {
                continue;
            }

            $zip->addFile($filePath, $relativePath);
        }

        $zip->close();

        $this->info("Project zipped successfully to: $zipPath");
        return 0;
    }
}
