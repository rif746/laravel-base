<?php

use App\Domains\Identity\Models\User;
use App\Domains\System\Actions\Files\PruneOrphanedFiles;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\File;
use App\Domains\System\Queries\GetSystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it cleans up database and disk orphans', function () {
    Storage::fake('public');

    // Setup GetSystemSettings mock
    $settingQuery = Mockery::mock(GetSystemSettings::class);
    $settingQuery->shouldReceive('get')->andReturnNull();

    // 1. Create a DB orphan: A record in 'files' table that references nothing
    File::create([
        'fileable_type' => User::class,
        'fileable_id' => '999', // Non-existent
        'relation_name' => 'avatar',
        'name' => 'db_orphan.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
        'disk' => 'public',
        'path' => 'avatars/db_orphan.txt',
        'uploader_id' => 'user1',
    ]);

    // 2. Create a physical orphan: A file on disk that isn't in the DB
    Storage::disk('public')->put('orphaned_file.txt', 'content');

    // 3. Create a valid file (should not be deleted)
    $user = User::factory()->create(['id' => '1']);
    File::create([
        'fileable_type' => User::class,
        'fileable_id' => $user->id,
        'relation_name' => 'avatar',
        'name' => 'valid.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
        'disk' => 'public',
        'path' => 'avatars/valid.txt',
        'uploader_id' => 'user1',
    ]);
    Storage::disk('public')->put('avatars/valid.txt', 'content');

    $action = new PruneOrphanedFiles($settingQuery);
    $stats = $action->execute('public', '/');

    // Assertions
    expect($stats['db_orphans_removed'])->toBe(1)
        ->and($stats['disk_orphans_removed'])->toBe(1);

    expect(File::count())->toBe(1); // Only the valid one remains
    Storage::disk('public')->assertMissing('orphaned_file.txt');
    Storage::disk('public')->assertExists('avatars/valid.txt');
});
