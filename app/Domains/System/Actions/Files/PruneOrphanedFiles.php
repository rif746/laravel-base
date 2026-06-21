<?php

namespace App\Domains\System\Actions\Files;

use App\Domains\System\Models\File;
use App\Domains\System\Queries\GetSystemSettings;
use Illuminate\Support\Facades\Storage;

class PruneOrphanedFiles
{
    public function __construct(protected GetSystemSettings $settingQuery)
    {
    }

    public function execute(string $disk = 'public', string $directory = '/'): array
    {
        $stats = ['db_orphans_removed' => 0, 'disk_orphans_removed' => 0];

        // --- SWEEP 1: Clean up orphaned Database Records ---
        // Find files where the parent model (fileable) has disappeared
        $orphanedRecords = File::whereDoesntHaveMorph('fileable', '*')->get();

        foreach ($orphanedRecords as $record) {
            // Because we set up the self-cleaning booted() method earlier,
            // calling delete() here will automatically remove the physical file too!
            $record->delete();
            $stats['db_orphans_removed']++;
        }

        // --- SWEEP 2: Clean up stranded Physical Files ---
        // Get all files physically sitting on the disk in the target directory
        $physicalFiles = Storage::disk($disk)->allFiles($directory);

        // Get all file paths currently tracked in the database
        $trackedFiles = File::pluck('path')->toArray();

        $settingFiles = collect(\App\Domains\System\Enums\SystemSettingKey::cases())
            ->filter(fn ($key) => $key->inputType() === \App\UI\Enums\InputType::FILE)
            ->map(fn ($key) => $this->settingQuery->get($key))
            ->filter()
            ->toArray();

        $trackedFiles = array_merge($trackedFiles, $settingFiles);
        $trackedFiles[] = 'images/logo.svg';

        // Compare the two arrays to find files on disk that the DB knows nothing about
        $strandedFiles = array_diff($physicalFiles, $trackedFiles);
        $strandedFiles = array_filter($strandedFiles, fn ($file) => basename($file) !== '.gitignore');

        if (! empty($strandedFiles)) {
            Storage::disk($disk)->delete($strandedFiles);
            $stats['disk_orphans_removed'] = count($strandedFiles);
        }

        return $stats;
    }
}
