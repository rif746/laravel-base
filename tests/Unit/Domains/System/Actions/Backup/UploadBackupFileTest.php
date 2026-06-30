<?php

namespace Tests\Unit\Domains\System\Actions\Backup;

use App\Domains\System\Actions\Backup\SyncBackupCatalog;
use App\Domains\System\Actions\Backup\UploadBackupFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class UploadBackupFileTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('test-disk');
        Config::set('backup.backup.destination.disks', ['test-disk']);
        Config::set('backup.backup.name', 'test-backup-dir');
    }

    public function test_it_stores_the_file_and_syncs_catalog(): void
    {
        $file = UploadedFile::fake()->create('backup.zip');

        $syncBackupCatalog = Mockery::mock(SyncBackupCatalog::class);
        $syncBackupCatalog->shouldReceive('execute')->once();

        $action = new UploadBackupFile($syncBackupCatalog);
        $action->execute($file);

        Storage::disk('test-disk')->assertExists('test-backup-dir/backup.zip');
    }
}
