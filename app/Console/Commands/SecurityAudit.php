<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SecurityAudit extends Command
{
    protected $signature = 'security:audit';
    protected $description = 'Run security audit on the application';

    public function handle()
    {
        $this->info('Running Security Audit...');
        $this->newLine();

        $issues = [];

        // Check environment configuration
        $issues = array_merge($issues, $this->checkEnvironment());

        // Check file permissions
        $issues = array_merge($issues, $this->checkFilePermissions());

        // Check for sensitive files
        $issues = array_merge($issues, $this->checkSensitiveFiles());

        // Display results
        if (empty($issues)) {
            $this->info('✅ No security issues found!');
        } else {
            $this->error('⚠️  Security issues found:');
            foreach ($issues as $issue) {
                $this->warn("  • {$issue}");
            }
        }

        return empty($issues) ? 0 : 1;
    }

    private function checkEnvironment()
    {
        $issues = [];

        // Check APP_DEBUG
        if (config('app.debug') === true && config('app.env') === 'production') {
            $issues[] = 'APP_DEBUG is enabled in production environment';
        }

        // Check APP_KEY
        if (empty(config('app.key'))) {
            $issues[] = 'APP_KEY is not set';
        }

        // Check session security
        if (!config('session.secure') && config('app.env') === 'production') {
            $issues[] = 'SESSION_SECURE_COOKIE should be true in production';
        }

        if (!config('session.http_only')) {
            $issues[] = 'SESSION_HTTP_ONLY should be true';
        }

        return $issues;
    }

    private function checkFilePermissions()
    {
        $issues = [];

        // Check .env file permissions
        if (File::exists(base_path('.env'))) {
            $perms = substr(sprintf('%o', fileperms(base_path('.env'))), -4);
            if ($perms !== '0600' && $perms !== '0644') {
                $issues[] = ".env file has insecure permissions ({$perms})";
            }
        }

        // Check storage directory
        if (!is_writable(storage_path())) {
            $issues[] = 'Storage directory is not writable';
        }

        return $issues;
    }

    private function checkSensitiveFiles()
    {
        $issues = [];

        $sensitiveFiles = [
            '.env.backup',
            '.env.example',
            'composer.lock',
            'package-lock.json'
        ];

        foreach ($sensitiveFiles as $file) {
            if (File::exists(public_path($file))) {
                $issues[] = "Sensitive file {$file} is accessible in public directory";
            }
        }

        return $issues;
    }
}
