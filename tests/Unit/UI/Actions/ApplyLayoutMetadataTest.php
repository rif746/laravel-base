<?php

namespace Tests\Unit\UI\Actions;

use App\UI\Actions\ResolveDynamicText;
use App\UI\Actions\ApplyLayoutMetadata;
use App\Attributes\LayoutData;
use Illuminate\Support\Facades\View;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

test('it applies layout metadata', function () {
    $textResolver = Mockery::mock(ResolveDynamicText::class);
    $action = new ApplyLayoutMetadata($textResolver);

    // We assume App\Attributes\LayoutData exists.
    $attributeInstance = new LayoutData(breadcrumbs: ['Home' => 'home.index'], header: 'Dashboard');
    $dataContext = [];

    $textResolver->shouldReceive('execute')->andReturnUsing(fn($v) => $v);

    View::shouldReceive('composer')->once();

    $action->execute($attributeInstance, $dataContext);
});
