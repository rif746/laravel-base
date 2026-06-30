<?php

namespace Tests\Feature\Domains\System\Actions\Backup;

use App\Domains\System\Actions\Backup\DeleteBackup;
use App\Domains\System\Models\Backup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteBackupTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_the_backup(): void
    {
        Storage::fake('test-disk');

        $backup = Backup::create([
            'file_name' => 'backup.zip',
            'disk' => 'test-disk',
            'path' => 'test-backup/backup.zip',
            'size' => 1024,
            'type' => 'full',
        ]);

        Storage::disk('test-disk')->put('test-backup/backup.zip', 'content');

        $action = new DeleteBackup();
        $action->execute($backup);

        Storage::disk('test-disk')->assertMissing('test-backup/backup.zip');
        $this->assertDatabaseMissing('backups', ['id' => $backup->id]);
    }
}
