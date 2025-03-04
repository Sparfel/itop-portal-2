<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


//Synchronisation des Organisations et Sites
app(Schedule::class)->command('ItopOrg:sync', [1])->daily();
app(Schedule::class)->command('ItopLoc:sync', [1])->daily();
