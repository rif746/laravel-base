<?php

uses()->group('architecture');

// Controllers should be ultra-lean entry points
arch('controllers layer strictness')
    ->expect('App\Http\Controllers')
    ->not->toUse([
        'App\Domains\Account\Models',
        'App\Domains\Identity\Models',
        'App\Domains\System\Models',
    ]); // Can't touch DB models directly

arch('actions are protected from model layer leakage')
    ->expect('App\Domains\*\Actions')
    ->not->toBeUsedIn([
        'App\Domains\*\Models',
    ]);

// Actions should be purely executable logic
arch('actions layer strictness')
    ->expect('App\Domains\*\Actions')
    ->toOnlyBeUsedIn([
        'App\Http\Controllers',
        'App\Http\Ingestion',
        'App\Console\Commands',
        'App\Domains\Account\Listeners',
        'App\Domains\Identity\Listeners',
        'App\Domains\System\Listeners',
        'App\Domains\Account\Actions',
        'App\Domains\Identity\Actions',
        'App\Domains\System\Actions',
        'App\Domains\Identity\Integration',
    ]);
