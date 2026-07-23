<?php

use App\Domains\System\Actions\Files\UploadAndAttachFile;
use App\Domains\System\DTOs\FileDTO;
use App\Domains\System\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it uploads and attaches a file', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('test.txt', 1024, 'text/plain');
    $dto = new FileDTO(
        modelType: 'User',
        modelId: '123',
        relationName: 'avatar',
        directory: 'avatars',
        disk: 'public',
        options: [],
        uploaderId: 'user1'
    );

    // We expect File::create to be called. Since File is a model, we can mock it or use a database transaction if we had RefreshDatabase
    // Let's use a simple mock for File if possible or just use the database

    $action = new UploadAndAttachFile;
    $result = $action->execute($file, $dto);

    expect($result)->toBeInstanceOf(File::class)
        ->and($result->name)->toBe('test.txt')
        ->and($result->disk)->toBe('public');

    Storage::disk('public')->assertExists($result->path);
});
