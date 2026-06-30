<?php

namespace Tests\Feature\Domains\System\Actions\Backup;

use App\Domains\System\Actions\Backup\SyncBackupCatalog;
use App\Domains\System\Models\Backup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SyncBackupCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_syncs_catalog_correctly(): void
    {
        Storage::fake('test-disk');
        Config::set('backup.backup.destination.disks', ['test-disk']);
        Config::set('backup.backup.name', 'test-backup');

        // Create a physical file
        Storage::disk('test-disk')->put('test-backup/backup1.zip', 'content');

        // Create an orphaned DB record
        Backup::create([
            'file_name' => 'test-backup backup2.zip',
            'disk' => 'test-disk',
            'path' => 'test-backup/backup2.zip',
            'size' => 1024,
            'type' => 'full',
        ]);

        $action = new SyncBackupCatalog();
        $action->execute();

        $this->assertDatabaseHas('backups', ['file_name' => 'test-backup backup1.zip']);
        $this->assertDatabaseMissing('backups', ['file_name' => 'test-backup backup2.zip']);
    }
}
