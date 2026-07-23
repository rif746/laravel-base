<?php

namespace App\Domains\System\Traits\Enum;

/*
 * @mixin \BackedEnum
 */

use BadMethodCallException;
use Illuminate\Support\Str;

trait HasPredicateMethod
{
    public function __call(string $method, array $arguments): bool
    {
        if (str_starts_with($method, 'is')) {
            $expectedCase = Str::substr($method, 2);
            $expectedCase = Str::upper($expectedCase);
            foreach ($this::cases() as $case) {
                if ($case->name === $expectedCase || $case->value === Str::kebab($expectedCase)) {
                    return $this === $case;
                }
            }

            throw new BadMethodCallException("Method {$method} does not exist on ".self::class);
        }

        return true;
    }
}
