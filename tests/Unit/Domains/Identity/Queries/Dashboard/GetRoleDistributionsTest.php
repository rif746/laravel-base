<?php

use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Queries\Dashboard\GetRoleDistributions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can fetch role distributions', function () {
    $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $editor = Role::create(['name' => 'editor', 'guard_name' => 'web']);

    $user1 = User::factory()->create();
    $user1->assignRole($admin);

    $user2 = User::factory()->create();
    $user2->assignRole($editor);

    $user3 = User::factory()->create();
    $user3->assignRole($editor);

    $query = new GetRoleDistributions;
    $results = $query->fetch();

    expect($results['categories'])->toContain('admin', 'editor')
        ->and($results['series'])->toContain(1, 2);
});
