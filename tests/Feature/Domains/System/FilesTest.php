<?php

use App\Domains\Identity\Models\User;
use App\Domains\System\Actions\Files\PruneOrphanedFiles;
use App\Domains\System\Actions\Files\ReplaceSingleFile;
use App\Domains\System\Actions\Files\UploadAndAttachFile;
use App\Domains\System\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('UploadAndAttachFile action uploads a file and attaches it polymorphically', function () {
    Storage::fake('private');

    $user = User::factory()->create();
    $uploadedFile = UploadedFile::fake()->image('document.jpg');

    $action = app(UploadAndAttachFile::class);
    $file = $action->execute(
        targetModel: $user,
        relationName: 'avatar',
        uploadedFile: $uploadedFile,
        disk: 'private',
        directory: 'avatars'
    );

    expect($file)->toBeInstanceOf(File::class);
    expect($file->name)->toBe('document.jpg');
    expect($file->disk)->toBe('private');
    expect($file->relation_name)->toBe('avatar');
    expect($file->fileable_id)->toBe($user->id);
    expect($file->fileable_type)->toBe(User::class);

    Storage::disk('private')->assertExists($file->path);
});

test('ReplaceSingleFile action replaces old file physically and in database', function () {
    Storage::fake('private');

    $user = User::factory()->create();
    $action = app(ReplaceSingleFile::class);

    // 1. Upload first file
    $file1 = UploadedFile::fake()->image('first.jpg');
    $res1 = $action->execute($user, 'avatar', $file1, 'private', 'avatars');

    $path1 = $res1->path;
    Storage::disk('private')->assertExists($path1);

    // 2. Upload second file (replace)
    $file2 = UploadedFile::fake()->image('second.jpg');
    $res2 = $action->execute($user, 'avatar', $file2, 'private', 'avatars');

    $path2 = $res2->path;
    Storage::disk('private')->assertMissing($path1);
    Storage::disk('private')->assertExists($path2);

    $user->refresh();
    expect($user->avatar()->count())->toBe(1);
    expect($user->avatar()->first()->name)->toBe('second.jpg');
});

test('PruneOrphanedFiles action cleans up database orphans and stranded physical files', function () {
    Storage::fake('public');

    // 1. Create a valid tracked file (should NOT be pruned)
    $user = User::factory()->create();
    $validFile = UploadedFile::fake()->image('valid.jpg');
    $validPath = $validFile->store('uploads', 'public');

    File::create([
        'fileable_id' => $user->id,
        'fileable_type' => User::class,
        'relation_name' => 'avatar',
        'name' => 'valid.jpg',
        'path' => $validPath,
        'disk' => 'public',
        'size' => $validFile->getSize(),
        'mime_type' => $validFile->getMimeType(),
    ]);

    // 2. Create a database record orphan (no parent model user)
    $dbOrphanFile = UploadedFile::fake()->image('db_orphan.jpg');
    $dbOrphanPath = $dbOrphanFile->store('uploads', 'public');

    File::create([
        'fileable_id' => 999999, // non-existent user ID
        'fileable_type' => User::class,
        'relation_name' => 'avatar',
        'name' => 'db_orphan.jpg',
        'path' => $dbOrphanPath,
        'disk' => 'public',
        'size' => $dbOrphanFile->getSize(),
        'mime_type' => $dbOrphanFile->getMimeType(),
    ]);

    // 3. Create a stranded physical file on disk (no database record)
    $strandedPath = Storage::disk('public')->put('uploads/stranded.jpg', 'fake file content');

    // Verify setup
    Storage::disk('public')->assertExists($validPath);
    Storage::disk('public')->assertExists($dbOrphanPath);
    Storage::disk('public')->assertExists('uploads/stranded.jpg');

    // 4. Run the pruner action
    $action = app(PruneOrphanedFiles::class);
    $stats = $action->execute('public', 'uploads');

    expect($stats['db_orphans_removed'])->toBe(1);
    expect($stats['disk_orphans_removed'])->toBe(1);

    // 5. Assert database and physical file status
    // Valid file is untouched
    Storage::disk('public')->assertExists($validPath);
    $this->assertDatabaseHas('files', ['path' => $validPath]);

    // Database orphan is deleted (both record and physical file)
    Storage::disk('public')->assertMissing($dbOrphanPath);
    $this->assertDatabaseMissing('files', ['path' => $dbOrphanPath]);

    // Stranded file is deleted
    Storage::disk('public')->assertMissing('uploads/stranded.jpg');
});
