<?php

use App\Domains\Identity\Models\User;
use App\Domains\Identity\Queries\Dashboard\GetMonthlyNewUsers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('it can fetch monthly new users and growth rate', function () {
    Carbon::setTestNow('2024-05-15');

    // Last month (April)
    User::factory()->count(2)->create(['created_at' => '2024-04-10']);

    // This month (May)
    User::factory()->count(3)->create(['created_at' => '2024-05-01']);

    $query = new GetMonthlyNewUsers();
    $results = $query->fetch();

    expect($results['new_users'])->toBe(3)
        ->and($results['growth_rate'])->toBe('+50.00%'); // (3-2)/2 * 100 = 50
});

test('it returns 100% growth when no users joined last month', function () {
    Carbon::setTestNow('2024-05-15');

    User::factory()->count(3)->create(['created_at' => '2024-05-01']);

    $query = new GetMonthlyNewUsers();
    $results = $query->fetch();

    expect($results['new_users'])->toBe(3)
        ->and($results['growth_rate'])->toBe('+100%');
});
