<?php

use App\Domains\System\Actions\Files\RemoveModelFile;
use App\Domains\System\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it removes all files associated with a model', function () {
    // Create some files
    File::create([
        'fileable_type' => 'User',
        'fileable_id' => '1',
        'relation_name' => 'avatar',
        'name' => 'file1.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
        'disk' => 'public',
        'path' => 'path/to/file1.txt',
        'uploader_id' => 'u1',
    ]);

    File::create([
        'fileable_type' => 'User',
        'fileable_id' => '1',
        'relation_name' => 'documents',
        'name' => 'file2.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
        'disk' => 'public',
        'path' => 'path/to/file2.txt',
        'uploader_id' => 'u1',
    ]);

    // Create a file for a different model/id to ensure no accidental deletion
    File::create([
        'fileable_type' => 'Post',
        'fileable_id' => '2',
        'relation_name' => 'attachments',
        'name' => 'file3.txt',
        'mime_type' => 'text/plain',
        'size' => 1024,
        'disk' => 'public',
        'path' => 'path/to/file3.txt',
        'uploader_id' => 'u1',
    ]);

    $action = new RemoveModelFile;
    $action->execute('User', '1');

    expect(File::where('fileable_type', 'User')->where('fileable_id', '1')->count())->toBe(0)
        ->and(File::where('fileable_type', 'Post')->where('fileable_id', '2')->count())->toBe(1);
});
