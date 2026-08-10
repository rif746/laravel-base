<?php

namespace App\Domains\System\Models;

use App\Domains\System\Enums\SystemSettingKey;
use Exception;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value', 'type'])]
#[WithoutTimestamps]
class SystemSettings extends Model
{
    public static string $cacheName = 'system-settings';

    /**
     * @throws Exception
     */
    public function getTranslatedValueAttribute(): ?string
    {
        $key = SystemSettingKey::tryFrom($this->attributes['key']);

        $schema = $key->getSchema();
        if ($schema->type->isFile() && isset($this->attributes['value'])) {
            return asset_static($this->attributes['value']);
        } elseif ($schema->type->isSelect()) {
            return $schema->attributes['options'][$this->attributes['value']];
        }

        return $this->attributes['value'];
    }
}
