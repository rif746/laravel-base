<?php

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

function temp_asset(string $path, $time = null, $options = [])
{
    if ($time == null) {
        $time = now()->addMinutes(2);
    }

    if (! Storage::disk(config('filesystems.default'))->exists($path)) {
        return url('/404');
    }

    if (! $path) {
        return;
    }

    $url = Storage::temporaryUrl($path, $time, $options);

    info([
        'url' => $url,
        'time_unix' => $time->unix(),
        'now' => now()->unix(),
    ]);

    return $url;
}

function upload_file(File $file, string $path) {}

function remove_file(string $path) {}

function jwt_extract(string $token)
{
    [$header, $payload, $signature] = explode('.', $token);
    $payload = base64_decode(strtr($payload, '-_', '+/'));

    return json_decode($payload, true);
}
