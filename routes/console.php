<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule monthly invoice generation on the 1st of each month at 9:00 AM
Schedule::command('invoices:generate-monthly')
    ->monthlyOn(1, '09:00')
    ->timezone('America/Argentina/Buenos_Aires');
