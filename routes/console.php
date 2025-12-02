<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule document alerts to run daily at 9:00 AM
Schedule::command('document:send-alerts')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->description('Send document contract start date alerts to customers');
