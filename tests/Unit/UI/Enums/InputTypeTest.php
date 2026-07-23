<?php

namespace Tests\Unit\UI\Enums;

use App\UI\Enums\InputType;
use Tests\TestCase;

uses(TestCase::class);

test('it returns correct component for each input type', function () {
    expect(InputType::TEXTAREA->component())->toBe('form.textarea')
        ->and(InputType::SELECT->component())->toBe('form.select')
        ->and(InputType::FILE->component())->toBe('filepond::upload')
        ->and(InputType::CHECKBOX->component())->toBe('form.checkbox')
        ->and(InputType::NUMBER->component())->toBe('form.input')
        ->and(InputType::TEXTLINE->component())->toBe('form.input');
});

test('it has correct labels', function () {
    expect(InputType::NUMBER->label())->toBe(__('ui/enum.input_type.number'))
        ->and(InputType::TEXTLINE->label())->toBe(__('ui/enum.input_type.text_line'))
        ->and(InputType::TEXTAREA->label())->toBe(__('ui/enum.input_type.text_area'));
});
