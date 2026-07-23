<?php

namespace Tests\Unit\UI\Enums;

use App\UI\Enums\FileType;
use Tests\TestCase;

uses(TestCase::class);

test('it returns correct mime types for each file type', function () {
    expect(FileType::DOCUMENT->mimeType())->toBe(['application/pdf', 'application/vnd.ms-word', 'application/vnd.oasis.opendocument.text'])
        ->and(FileType::IMAGE->mimeType())->toBe(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'])
        ->and(FileType::AUDIO->mimeType())->toBe(['audio/mpeg', 'audio/mp4', 'audio/ogg', 'audio/webm', 'audio/x-wav', 'audio/aac']);
});

test('it has correct labels', function () {
    expect(FileType::DOCUMENT->label())->toBe(__('ui/enum.file_type.document'))
        ->and(FileType::IMAGE->label())->toBe(__('ui/enum.file_type.image'))
        ->and(FileType::AUDIO->label())->toBe(__('ui/enum.file_type.audio'));
});
