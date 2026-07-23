<?php

uses()->group('architecture');

arch('system domain isolation')
    ->expect('App\Domains\System')
    ->not->toUse('App\Domains\Identity')
    ->ignoring([
        'App\Domains\System\Listeners',
        'App\Domains\System\Policies',
        'App\Domains\System\Providers\SystemServiceProvider',
    ]);
