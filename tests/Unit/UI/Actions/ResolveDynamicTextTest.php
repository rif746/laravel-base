<?php

namespace Tests\Unit\UI\Actions;

use App\UI\Actions\ResolveDynamicText;
use Tests\TestCase;

uses(TestCase::class);

test('it resolves dynamic text placeholders', function () {
    $action = new ResolveDynamicText();

    $component = new class {
        public $user = ['name' => 'John'];
    };

    $result = $action->execute('Hello {user.name}', $component, 'user');
    expect($result)->toBe('Hello John');
});
