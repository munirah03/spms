<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('orders:archive-reset')->dailyAt('21:00');
Schedule::command('uploads:purge')->hourly();
Schedule::command('archives:purge')->dailyAt('21:30');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


