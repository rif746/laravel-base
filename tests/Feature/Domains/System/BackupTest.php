<?php

use App\Domains\System\Actions\Backup\DeleteBackup;
use App\Domains\System\Actions\Backup\SyncBackupCatalog;
use App\Domains\System\Actions\Backup\SystemBackup;
use App\Domains\System\Actions\Backup\SystemRestore;
use App\Domains\System\Actions\Backup\UploadBackupFile;
use App\Domains\System\Models\Backup;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    config(['backup.backup.destination.disks' => ['local']]);
    config(['backup.backup.name' => 'Laravel']);
    Storage::fake('local');
});

test('SyncBackupCatalog action scans disk, creates backups, and prunes ghost records', function () {
    Storage::disk('local')->put('Laravel/backup-1.zip', 'dummy content');
    Storage::disk('local')->put('Laravel/backup-2.zip', 'dummy content');
    Storage::disk('local')->put('Laravel/readme.txt', 'readme');

    $action = app(SyncBackupCatalog::class);
    $action->execute();

    $this->assertDatabaseHas('backups', [
        'path' => 'Laravel/backup-1.zip',
        'disk' => 'local',
    ]);
    $this->assertDatabaseHas('backups', [
        'path' => 'Laravel/backup-2.zip',
        'disk' => 'local',
    ]);

    Storage::disk('local')->delete('Laravel/backup-1.zip');
    $action->execute();

    $this->assertDatabaseMissing('backups', [
        'path' => 'Laravel/backup-1.zip',
    ]);
    $this->assertDatabaseHas('backups', [
        'path' => 'Laravel/backup-2.zip',
    ]);
});

test('DeleteBackup action deletes physical file and DB record', function () {
    Storage::disk('local')->put('Laravel/backup-delete.zip', 'dummy');

    $backup = Backup::create([
        'file_name' => 'Laravel backup-delete.zip',
        'disk' => 'local',
        'path' => 'Laravel/backup-delete.zip',
        'size' => 10,
        'type' => 'full',
    ]);

    $action = app(DeleteBackup::class);
    $action->execute($backup);

    Storage::disk('local')->assertMissing('Laravel/backup-delete.zip');
    $this->assertDatabaseMissing('backups', [
        'id' => $backup->id,
    ]);
});

test('UploadBackupFile action stores backup zip and syncs catalog', function () {
    $file = UploadedFile::fake()->create('uploaded-backup.zip', 100);

    $action = app(UploadBackupFile::class);
    $action->execute($file);

    Storage::disk('local')->assertExists('Laravel/uploaded-backup.zip');
    $this->assertDatabaseHas('backups', [
        'path' => 'Laravel/uploaded-backup.zip',
    ]);
});

test('SystemBackup action executes command, verifies file, and returns model', function () {
    Artisan::shouldReceive('call')
        ->with('backup:run', [])
        ->once()
        ->andReturn(0);

    Storage::disk('local')->put('Laravel/new-artisan-backup.zip', 'dummy data');

    $action = app(SystemBackup::class);
    $backup = $action->execute();

    expect($backup)->toBeInstanceOf(Backup::class);
    expect($backup->path)->toBe('Laravel/new-artisan-backup.zip');
    $this->assertDatabaseHas('backups', [
        'path' => 'Laravel/new-artisan-backup.zip',
    ]);
});

test('SystemBackup action throws exception if command fails', function () {
    Artisan::shouldReceive('call')
        ->with('backup:run', [])
        ->once()
        ->andReturn(1);

    $action = app(SystemBackup::class);

    expect(fn () => $action->execute())->toThrow(Exception::class, 'Failed to execute backup');
});

test('SystemRestore action extracts backup, runs database process, and copies files', function () {
    Process::fake();

    $tempZipPath = storage_path('framework/testing/disks/local/Laravel/restore-test.zip');
    File::ensureDirectoryExists(dirname($tempZipPath));

    $zip = new ZipArchive;
    if ($zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        $zip->addFromString('db-dumps/mysql-laravel.sql', 'SELECT 1;');
        $zip->addFromString('storage/my-file.txt', 'hello from backup');
        $zip->close();
    }

    $backup = Backup::create([
        'file_name' => 'Laravel restore-test.zip',
        'disk' => 'local',
        'path' => 'Laravel/restore-test.zip',
        'size' => filesize($tempZipPath),
        'type' => 'full',
    ]);

    config(['database.connections.mysql.database' => 'laravel']);
    config(['database.connections.mysql.username' => 'root']);
    config(['database.connections.mysql.password' => 'secret']);
    config(['database.connections.mysql.host' => '127.0.0.1']);

    $action = app(SystemRestore::class);
    $action->execute($backup);

    Process::assertRan(function ($process) {
        return str_contains($process->command[0], 'mysql');
    });

    $restoredFile = storage_path('my-file.txt');
    expect(file_exists($restoredFile))->toBeTrue();
    expect(file_get_contents($restoredFile))->toBe('hello from backup');

    if (file_exists($restoredFile)) {
        unlink($restoredFile);
    }
});
