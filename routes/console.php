<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Backup scheduling
Artisan::command('backup:schedule', function () {
    // Daily database backup at 2 AM
    $this->call('backup:system', ['--type' => 'database']);
    $this->info('Daily database backup completed');
})->purpose('Run scheduled database backup')->dailyAt('02:00');

Artisan::command('backup:weekly', function () {
    // Weekly full backup on Sunday at 3 AM
    $this->call('backup:system', ['--type' => 'full']);
    $this->info('Weekly full backup completed');
})->purpose('Run weekly full backup')->weeklyOn(0, '03:00');
