<?php

use App\Domains\Identity\Enums\UserStatus;
use App\Domains\System\Enums\FileType;

test('UserStatus enum returns correct label and badge HTML', function () {
    $active = UserStatus::ACTIVE;
    $inactive = UserStatus::INACTIVE;

    expect($active->labels())->toBeString();
    expect($inactive->labels())->toBeString();

    expect($active->badge())->toContain('bg-success');
    expect($inactive->badge())->toContain('bg-danger');
});

test('FileType enum returns correct mime types', function () {
    $image = FileType::IMAGE;
    $document = FileType::DOCUMENT;
    $audio = FileType::AUDIO;

    expect($image->mimeType())->toContain('image/jpeg');
    expect($document->mimeType())->toContain('application/pdf');
    expect($audio->mimeType())->toContain('audio/mpeg');
});
