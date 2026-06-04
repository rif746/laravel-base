<?php

namespace App\Casts;

use App\Support\ValueObjects\ByteUsage;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ByteHumanReadable implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?ByteUsage
    {
        if (is_null($value)) {
            return null;
        }

        return new ByteUsage((int) $value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof ByteUsage) {
            return $value->bytes;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        throw new InvalidArgumentException("The {$key} attribute must be an integer or an instance of ByteUsage.");
    }
}
