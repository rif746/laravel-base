<?php

namespace Tests\Unit\UI\Enums;

use App\UI\Enums\InputType;

test('it returns correct component for each input type', function () {
    expect(InputType::TEXTAREA->component())->toBe('form.textarea')
        ->and(InputType::SELECT->component())->toBe('form.select')
        ->and(InputType::FILE->component())->toBe('filepond::upload')
        ->and(InputType::CHECKBOX->component())->toBe('form.checkbox')
        ->and(InputType::NUMBER->component())->toBe('form.input')
        ->and(InputType::TEXTLINE->component())->toBe('form.input');
});
