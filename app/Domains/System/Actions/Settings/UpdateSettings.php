<?php

namespace App\Domains\System\Actions\Settings;

use App\Domains\System\DTOs\SystemSetingDTO;
use App\Domains\System\Models\SystemSettings;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UpdateSettings
{
    public function execute(SystemSetingDTO $dto): void
    {
        $value = $dto->value;

        if ($dto->key->schema()->type->isFile()) {
            $currentSettings = SystemSettings::where('key', $dto->key->value)->value('value');

            if ($currentSettings) {
                remove_file($currentSettings);
            }

            if ($value instanceof TemporaryUploadedFile) {
                $value = upload_file(
                    file: $value,
                    path: '/system/settings/'.$dto->key->value,
                    disk: 'public'
                );
            }
        }

        SystemSettings::updateOrCreate(
            ['key' => $dto->key->value],
            ['value' => $value],
        );

        cache()->forget(SystemSettings::$cacheName);
    }
}
