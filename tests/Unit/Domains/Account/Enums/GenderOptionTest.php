<?php

use App\Domains\Account\Enums\GenderOption;
use Tests\TestCase;

uses(TestCase::class);

test('it has correct labels', function () {
    expect(GenderOption::MALE->label())->toBe(__('domains/account/enum.gender.male'))
        ->and(GenderOption::FEMALE->label())->toBe(__('domains/account/enum.gender.female'));
});
