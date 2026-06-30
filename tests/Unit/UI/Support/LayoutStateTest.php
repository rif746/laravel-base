<?php

namespace Tests\Unit\UI\Support;

use App\UI\Support\LayoutState;
use Tests\TestCase;

test('it can set and get breadcrumbs', function () {
    $layoutState = new LayoutState();
    $breadcrumbs = [
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Dashboard', 'url' => '/dashboard'],
    ];

    $layoutState->setBreadcrumbs($breadcrumbs);

    expect($layoutState->getBreadcrumbs())->toBe($breadcrumbs);
});
