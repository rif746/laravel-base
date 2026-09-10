<?php

namespace App\Domains\System\Traits\Model;

use App\Domains\System\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @mixin Model
 */
trait HasFile
{
    public function hasSingleFile(string $relation): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')
            ->where('relation_name', $relation);
    }

    public function hasMultiFile(string $relation): MorphMany
    {
        return $this->morphMany(File::class, 'fileable')
            ->where('relation_name', $relation);
    }
}
