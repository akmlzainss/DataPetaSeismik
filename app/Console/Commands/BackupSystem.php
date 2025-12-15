<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupSystem extends Command
{
    protected $signature = 'backup:system {--type=full : Type of backup (full, database, files)}';
    protected $description = 'Create system backup (database and files)';

    public function handle()
    {
        $type = $this->option('type');
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');

        $this->info("Starting {$type} backup...");

        try {
            switch ($type) {
                case 'database':
                    $this->backupDatabase($timestamp);
                    break;
                case 'files':
                    $this->backupFiles($timestamp);
                    break;
                case 'full':
                default:
                    $this->backupDatabase($timestamp);
                    $this->backupFiles($timestamp);
                    break;
            }

            $this->info('Backup completed successfully!');
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function backupDatabase($timestamp)
    {
        $this->info('Backing up database...');

        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');

        $backupPath = storage_path("app/backups/database/backup_{$timestamp}.sql");

        // Create backup directory if it doesn't exist
        if (!file_exists(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0755, true);
        }

        // Use mysqldump command
        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s %s > %s',
            escapeshellarg($dbHost),
            escapeshellarg($dbUser),
            escapeshellarg($dbPass),
            escapeshellarg($dbName),
            escapeshellarg($backupPath)
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception('Database backup failed');
        }

        $this->info("Database backup saved to: {$backupPath}");
    }

    private function backupFiles($timestamp)
    {
        $this->info('Backing up uploaded files...');

        $sourcePath = storage_path('app/public');
        $backupPath = storage_path("app/backups/files/files_{$timestamp}.zip");

        // Create backup directory if it doesn't exist
        if (!file_exists(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0755, true);
        }

        // Create ZIP archive
        $zip = new \ZipArchive();
        if ($zip->open($backupPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            throw new \Exception('Cannot create ZIP file');
        }

        $this->addDirectoryToZip($zip, $sourcePath, '');
        $zip->close();

        $this->info("Files backup saved to: {$backupPath}");
    }

    private function addDirectoryToZip($zip, $dir, $zipPath)
    {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $filePath = $dir . '/' . $file;
                    $zipFilePath = $zipPath . $file;

                    if (is_dir($filePath)) {
                        $zip->addEmptyDir($zipFilePath);
                        $this->addDirectoryToZip($zip, $filePath, $zipFilePath . '/');
                    } else {
                        $zip->addFile($filePath, $zipFilePath);
                    }
                }
            }
        }
    }
}
