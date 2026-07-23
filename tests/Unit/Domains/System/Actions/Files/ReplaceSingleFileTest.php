<?php

use App\Domains\System\Actions\Files\ReplaceSingleFile;
use App\Domains\System\Actions\Files\UploadAndAttachFile;
use App\Domains\System\DTOs\FileDTO;
use App\Domains\System\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it replaces an existing file', function () {
    Storage::fake('public');

    // Create an old file in the database
    $oldFile = File::create([
        'fileable_type' => 'User',
        'fileable_id' => '123',
        'relation_name' => 'avatar',
        'name' => 'old.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
        'disk' => 'public',
        'path' => 'avatars/old.txt',
        'uploader_id' => 'user1',
    ]);

    // Mock the upload action
    $uploadAction = Mockery::mock(UploadAndAttachFile::class);

    $newFile = UploadedFile::fake()->create('new.txt', 2048, 'text/plain');
    $dto = new FileDTO(
        modelType: 'User',
        modelId: '123',
        relationName: 'avatar',
        directory: 'avatars',
        disk: 'public',
        options: [],
        uploaderId: 'user1'
    );

    $newFileRecord = new File([
        'fileable_type' => 'User',
        'fileable_id' => '123',
        'relation_name' => 'avatar',
        'name' => 'new.txt',
        'mime_type' => 'text/plain',
        'size' => 2048,
        'disk' => 'public',
        'path' => 'avatars/new.txt',
        'uploader_id' => 'user1',
    ]);

    $uploadAction->shouldReceive('execute')
        ->once()
        ->with($newFile, $dto)
        ->andReturn($newFileRecord);

    $action = new ReplaceSingleFile($uploadAction);
    $result = $action->execute($newFile, $dto);

    // Check old file is deleted
    expect(File::find($oldFile->id))->toBeNull();
    // Check new file returned
    expect($result->name)->toBe('new.txt');
});
