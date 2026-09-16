<?php

namespace App\Domains\System\Actions\Settings;

use App\Domains\System\Actions\Files\ConvertFileMedia;
use App\Domains\System\DTOs\SystemSetingDTO;
use App\Domains\System\Models\SystemSettings;
use Illuminate\Http\UploadedFile;

class UpdateSettings
{
    /**
     * Create a new Action instance with composed media converter dependency.
     *
     * @param ConvertFileMedia $mediaConverter
     */
    public function __construct(
        protected ConvertFileMedia $mediaConverter
    ) {}

    /**
     * Execute updating or creating system settings and handle media conversion for file types.
     *
     * @param SystemSetingDTO $dto
     * @return void
     */
    public function execute(SystemSetingDTO $dto): void
    {
        $value = $dto->value;
        $schema = $dto->key->getSchema();

        if ($schema->type->isFile()) {
            $currentSettings = SystemSettings::where('key', $dto->key->value)->value('value');

            // Delete old file from storage if updating an existing file setting
            if ($currentSettings && is_string($currentSettings)) {
                remove_file($currentSettings);
            }

            // Process temporary uploaded file via ConvertFileMedia action
            if ($value instanceof UploadedFile) {
                $directory = 'system/settings/' . $dto->key->value;
                $disk = 'public';

                // Execute conversion and compression (e.g., auto-convert raster images to WebP)
                [$storedPath] = $this->mediaConverter->execute(
                    file: $value,
                    directory: $directory,
                    disk: $disk
                );

                $value = $storedPath;
            }
        }

        // Persist setting record into database
        SystemSettings::updateOrCreate(
            ['key' => $dto->key->value],
            ['value' => $value],
        );

        // Invalidate system settings cache entry
        cache()->forget(SystemSettings::$cacheName);
    }
}
