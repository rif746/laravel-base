<?php

use App\Domains\Identity\Models\User;
use App\Domains\Identity\Queries\Dashboard\GetUserVerificationRates;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('it can fetch user verification rates', function () {
    // Create 3 verified users
    User::factory()->count(3)->create([
        'email_verified_at' => now(),
    ]);

    // Create 2 unverified users
    User::factory()->count(2)->create([
        'email_verified_at' => null,
    ]);

    $query = new GetUserVerificationRates();
    $results = $query->fetch();

    expect($results)->toBe([
        'verified' => 3,
        'unverified' => 2,
        'verification_rate' => 60.0, // (3/5) * 100
    ]);
});

test('it returns zero rates when no users exist', function () {
    $query = new GetUserVerificationRates();
    $results = $query->fetch();

    expect($results)->toBe([
        'verified' => 0,
        'unverified' => 0,
        'verification_rate' => 0,
    ]);
});
