<?php

use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Queries\Lookup\RoleLookup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can fetch roles without search', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);
    Role::create(['name' => 'editor', 'guard_name' => 'web']);

    $lookup = new RoleLookup;
    $results = $lookup->fetch(null);

    expect($results)->toHaveCount(2)
        ->and($results->pluck('name'))->toContain('admin', 'editor');
});

test('it can filter roles by name', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);
    Role::create(['name' => 'editor', 'guard_name' => 'web']);
    Role::create(['name' => 'viewer', 'guard_name' => 'web']);

    $lookup = new RoleLookup;

    $results = $lookup->fetch('edit');
    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('editor');

    $results = $lookup->fetch('v');
    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('viewer');
});

test('it returns empty collection when no roles match', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);

    $lookup = new RoleLookup;
    $results = $lookup->fetch('non-existent');

    expect($results)->toBeEmpty();
});
