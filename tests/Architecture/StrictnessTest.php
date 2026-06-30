<?php

uses()->group('architecture');

arch('no debugging statement left')
    ->expect(['dd', 'dump', 'var_dump'])
    ->not->toBeUsed();
