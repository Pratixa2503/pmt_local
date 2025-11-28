<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Schedule;
>>>>>>> 9d9ed85b (for cleaner setup)

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
<<<<<<< HEAD
=======

// Schedule document alerts to run daily at 9:00 AM
Schedule::command('document:send-alerts')
    ->dailyAt('09:00')
    ->description('Send document contract start date alerts to customers');
>>>>>>> 9d9ed85b (for cleaner setup)
