<?php

namespace App\UI\Enums;

use App\UI\Enums\Concerns\InteractsWithLabels;
use App\UI\Enums\Contracts\HasLabel;

enum FileType: string implements HasLabel
{
    use InteractsWithLabels;
    case DOCUMENT = 'document';
    case IMAGE = 'image';
    case AUDIO = 'audio';

    public function mimeType(): array
    {
        return match ($this) {
            self::DOCUMENT => ['application/pdf', 'application/vnd.ms-word', 'application/vnd.oasis.opendocument.text'],
            self::IMAGE => ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
            self::AUDIO => ['audio/mpeg', 'audio/mp4', 'audio/ogg', 'audio/webm', 'audio/x-wav', 'audio/aac'],

            default => ['text/plain'],
        };
    }
}
