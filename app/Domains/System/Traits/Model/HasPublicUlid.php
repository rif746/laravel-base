<?php

namespace App\Domains\System\Traits\Model;

use Illuminate\Support\Str;

trait HasPublicUlid
{
    public static function bootHasPublicUlid(): void
    {
        static::creating(function ($model) {
            $model->ulid = Str::ulid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
