<?php

namespace App\Domains\System\Models;

use App\Domains\System\Enums\SystemSettingKey;
use App\UI\Enums\InputType;
use Exception;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value'])]
#[WithoutTimestamps]
class SystemSettings extends Model
{
    /**
     * @throws Exception
     */
    public function getTranslatedValueAttribute(): ?string
    {
        $key = SystemSettingKey::tryFrom($this->attributes['key'])->schema();
        if ($key->type->isFile() && isset($this->attributes['value'])) {
            return asset_static($this->attributes['value']);
        } elseif ($key->type->isSelect()) {
            return $key->options[$this->attributes['value']];
        }

        return $this->attributes['value'];
    }
}
