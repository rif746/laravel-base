<?php

namespace Tests\Unit\UI\Enums;

use App\UI\Enums\FileType;
use Tests\TestCase;

test('it returns correct mime types for each file type', function () {
    expect(FileType::DOCUMENT->mimeType())->toBe(['application/pdf', 'application/vnd.ms-word', 'application/vnd.oasis.opendocument.text'])
        ->and(FileType::IMAGE->mimeType())->toBe(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'])
        ->and(FileType::AUDIO->mimeType())->toBe(['audio/mpeg', 'audio/mp4', 'audio/ogg', 'audio/webm', 'audio/x-wav', 'audio/aac']);
});
