<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

if (!function_exists('asset_static')) {
    /**
     * @throws Exception
     */
    function asset_static(string $path, $time = null, $options = []): bool|string
    {
        if ($path === '') {
            throw new Exception('Path is required.');
        }

        if ($time === null) {
            $time = now()->addMinutes(5);
        }

        if (Storage::disk('local')->exists($path)) {
            return Storage::temporaryUrl($path, $time, $options);
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return false;
    }
}

if (!function_exists('upload_file')) {
    function upload_file(UploadedFile|TemporaryUploadedFile $file, string $path, string $disk = 'local'): bool|string
    {
        return $file->store($path, $disk);
    }
}

if (!function_exists('remove_file')) {
    function remove_file(string $path): void
    {
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
