<?php

use App\Domains\System\Support\ValueObjects\ByteUsage;

test('it throws exception for negative bytes', function () {
    expect(fn() => new ByteUsage(-1))->toThrow(InvalidArgumentException::class, 'Bytes cannot be negative.');
});

test('it formats 0 bytes correctly', function () {
    $usage = new ByteUsage(0);
    expect($usage->format())->toBe('0 B');
});

test('it formats bytes to B correctly', function () {
    $usage = new ByteUsage(500);
    expect($usage->format())->toBe('500 B');
});

test('it formats bytes to KB correctly', function () {
    $usage = new ByteUsage(1024);
    expect($usage->format())->toBe('1 KB');
});

test('it formats bytes to MB correctly', function () {
    $usage = new ByteUsage(1024 * 1024);
    expect($usage->format())->toBe('1 MB');
});

test('it formats bytes to GB correctly', function () {
    $usage = new ByteUsage(1024 * 1024 * 1024);
    expect($usage->format())->toBe('1 GB');
});

test('it formats with custom precision', function () {
    $usage = new ByteUsage(1024 + 512); // 1.5 KB
    expect($usage->format(1))->toBe('1.5 KB');
});

test('it implements Stringable', function () {
    $usage = new ByteUsage(1024);
    expect((string) $usage)->toBe('1 KB');
});
