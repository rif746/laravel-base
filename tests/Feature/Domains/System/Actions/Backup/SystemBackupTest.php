<?php

namespace Tests\Feature\Domains\System\Actions\Backup;

use App\Domains\System\Actions\Backup\SystemBackup;
use App\Domains\System\Models\Backup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SystemBackupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('test-disk');
        Config::set('backup.backup.destination.disks', ['test-disk']);
        Config::set('backup.backup.name', 'test-backup');
    }

    public function test_it_creates_a_backup_record(): void
    {
        Artisan::shouldReceive('call')
            ->with('backup:run', [])
            ->once()
            ->andReturn(0);

        Storage::disk('test-disk')->put('test-backup/test-file.zip', 'content');

        $action = new SystemBackup;
        $backup = $action->execute();

        $this->assertInstanceOf(Backup::class, $backup);
        $this->assertEquals('test-backup test-file.zip', $backup->file_name);
        $this->assertEquals('test-disk', $backup->disk);
        $this->assertEquals('test-backup/test-file.zip', $backup->path);

        $this->assertEquals(7, $backup->getRawOriginal('size'));

        $this->assertDatabaseHas('backups', [
            'file_name' => 'test-backup test-file.zip',
            'disk' => 'test-disk',
            'path' => 'test-backup/test-file.zip',
            'size' => 7,
            'type' => 'full',
        ]);
    }
}
