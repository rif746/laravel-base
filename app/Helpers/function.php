<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

/**
 * Upload File
 *
 * @param Livewire\Features\SupportFileUploads\TemporaryUploadedFile $file
 * @param string $collection
 *
 * @return string
 */
function uploadFile(TemporaryUploadedFile|UploadedFile $file, $collection = ""): string
{
    $mime = $file->getMimeType();
    $extension = "." . $file->getClientOriginalExtension();
    $collection = $collection != "" ? $collection . "/" : null;
    $path = public_path('/storage/');
    if (Str::contains($mime, "video")) {
        $path = "videos/";
        $gfile = $file->get();
    } elseif (Str::contains($mime, "image")) {
        $path = "images/";
        $extension = ".webp";
        $gfile = Image::make($file)->encode("webp");
    } else {
        $path = "documents/";
        $gfile = $file->get();
    }
    
    $path = $path . $collection;
    ensureDirExists($path);
    $path = $path . sha1(Str::uuid()) . $extension;
    Storage::put($path, $gfile);
    return $path;
}

function ensureDirExists($dir)
{
    $dir = public_path($dir);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

/**
 * check and remove file if available
 *
 * @param string $file
 *
 * @return void
 */
function deleteFile($file)
{
    if (Storage::fileExists(public_path('/storage/' . $file))) {
        unlink(public_path("/storage/" . $file));
    }
}

/**
 * Check user roles
 * using spatie/laravel-permission package
 * used for auth()->user() method
 *
 * @param App\Models\User $user
 * @param mixed $role
 *
 * @return bool
 */
function roleCheck($user, $role)
{
    if (is_null($user)) return false;
    return $user->hasRole($role);
}

/**
 * Check file type
 * example: 
 * isFileType(public_path('/images/logo.png'), 'image') -> true
 * isFileType(public_path('/images/logo.png'), 'video') -> false
 * 
 * @param string $path
 * @param string $type
 * 
 * @return bool
 */
function isFileType(string $path, string $type): bool
{
    if ((file_exists($path) && is_string($path) && $path != "")) {
        $mime = File::mimeType($path);
        return Str::contains($mime, $type);
    }
    return false;
}