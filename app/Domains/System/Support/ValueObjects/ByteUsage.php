<?php

namespace App\Domains\System\Support\ValueObjects;

use InvalidArgumentException;
use Stringable;

class ByteUsage implements Stringable
{
    public function __construct(public int $bytes)
    {
        if ($this->bytes < 0) {
            throw new InvalidArgumentException('Bytes cannot be negative.');
        }
    }

    /**
     * Format the bytes into a human-readable string.
     */
    public function format(int $precision = 2): string
    {
        if ($this->bytes === 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'];
        $base = 1024;

        // Calculate the magnitude (0 for B, 1 for KB, etc.)
        $class = min((int) log($this->bytes, $base), count($units) - 1);

        // Calculate the formatted value
        $value = round($this->bytes / pow($base, $class), $precision);

        return sprintf('%s %s', $value, $units[$class]);
    }

    /**
     * Automatically format when echoed in Blade (e.g., {{ $model->bandwidth }}).
     */
    public function __toString(): string
    {
        return $this->format();
    }
}
