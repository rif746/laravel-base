<?php

namespace App\UI\Enums\Concerns;

use BackedEnum;
use Illuminate\Support\Str;

/**
 * Label interaction utilities for Enums.
 * This trait will provide methods for interacting with labels.
 * The label() method returns the translated label for the Enum value.
 * The key is from domains/<domains>/enum.enum-slugs.<value>
 *
 * @mixin BackedEnum
 */
trait InteractsWithLabels
{
    public function label(): string
    {
        $fqcn = static::class;

        if (Str::startsWith($fqcn, 'App\\Domains\\')) {
            $domain = Str::between($fqcn, 'App\\Domains\\', '\\Enums\\');
            $domainSlug = Str::kebab($domain);

            $className = Str::afterLast($fqcn, '\\');
            $enumSlug = Str::snake($className);

            $translationKey = "domains/{$domainSlug}/enum.{$enumSlug}.{$this->value}";
        } elseif (Str::startsWith($fqcn, 'App\\UI\\Enums\\')) {
            $className = Str::afterLast($fqcn, '\\');
            $enumSlug = Str::snake($className);

            $translationKey = "ui/enum.{$enumSlug}.{$this->value}";
        } else {
            $className = Str::afterLast($fqcn, '\\');
            $enumSlug = Str::snake($className);
            $translationKey = "enum.{$enumSlug}.{$this->value}";
        }

        return __($translationKey);
    }

    public static function options(): array
    {
        $mappedArray = array_map(fn (self $self) => [
            $self->value => $self->label(),
        ], self::cases());

        return array_merge(...$mappedArray);
    }
}
