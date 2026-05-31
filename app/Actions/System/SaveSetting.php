<?php

namespace App\Actions\System;

use App\DTOs\System\SystemSetingDTO;
use App\Models\System\SystemSettings;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SaveSetting
{
    public function execute(SystemSetingDTO $dto): void
    {
        $value = $dto->value;

        if ($dto->key->isImage()) {
            $currentSettings = SystemSettings::where('key', $dto->key->value)->value('value');

            if ($currentSettings) {
                remove_file($currentSettings);
            }

            if ($value instanceof TemporaryUploadedFile) {
                $value = upload_file(
                    file: $value,
                    path: '/system/settings/' . $dto->key->value,
                    disk: 'public'
                );
            }
        }

        SystemSettings::updateOrCreate(
            ['key' => $dto->key->value],
            ['value' => $value],
        );

        $dto->key->effect($dto->key->value, $value);

        cache()->forget('system-settings');
    }
}
