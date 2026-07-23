<?php

use App\Domains\Identity\Models\User;
use App\Domains\Identity\Queries\Dashboard\GetUserGrowthTrends;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can fetch user growth trends', function () {
    Carbon::setTestNow('2024-05-15');

    // January
    User::factory()->create(['created_at' => '2024-01-01']);
    User::factory()->create(['created_at' => '2024-01-15']);

    // March
    User::factory()->create(['created_at' => '2024-03-10']);

    // May
    User::factory()->create(['created_at' => '2024-05-01']);

    $query = new GetUserGrowthTrends;
    $results = $query->fetch();

    expect($results['categories'])->toHaveCount(12)
        ->and($results['categories'][0])->toBe('January')
        ->and($results['series'])->toHaveCount(12)
        ->and($results['series'][0])->toBe(2) // Jan
        ->and($results['series'][1])->toBe(0) // Feb
        ->and($results['series'][2])->toBe(1) // Mar
        ->and($results['series'][3])->toBe(0) // Apr
        ->and($results['series'][4])->toBe(1); // May
});
