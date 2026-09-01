<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('queue:work --max-time=55')
    ->everyMinute()
    ->withoutOverlapping();
