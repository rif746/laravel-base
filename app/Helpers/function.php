<?php

use Illuminate\Http\File;

function upload_file(File $file, string $path) {}

function remove_file(string $path) {}

function jwt_extract(string $token)
{
    [$header, $payload, $signature] = explode('.', $token);
    $payload = base64_decode(strtr($payload, '-_', '+/'));
    return json_decode($payload, true);
}
