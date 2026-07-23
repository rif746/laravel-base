<?php

use App\Domains\System\Casts\ByteHumanReadable;
use App\Domains\System\Support\ValueObjects\ByteUsage;
use Illuminate\Database\Eloquent\Model;

test('it casts bytes to ByteUsage', function () {
    $cast = new ByteHumanReadable;
    $model = new class extends Model {};

    $result = $cast->get($model, 'bytes', 1024, []);

    expect($result)->toBeInstanceOf(ByteUsage::class)
        ->and($result->bytes)->toBe(1024);
});

test('it returns null for null value in get', function () {
    $cast = new ByteHumanReadable;
    $model = new class extends Model {};

    expect($cast->get($model, 'bytes', null, []))->toBeNull();
});

test('it sets integer to integer', function () {
    $cast = new ByteHumanReadable;
    $model = new class extends Model {};

    expect($cast->set($model, 'bytes', 1024, []))->toBe(1024);
});

test('it sets ByteUsage to bytes', function () {
    $cast = new ByteHumanReadable;
    $model = new class extends Model {};
    $usage = new ByteUsage(1024);

    expect($cast->set($model, 'bytes', $usage, []))->toBe(1024);
});

test('it returns null for null value in set', function () {
    $cast = new ByteHumanReadable;
    $model = new class extends Model {};

    expect($cast->set($model, 'bytes', null, []))->toBeNull();
});

test('it throws exception for invalid type in set', function () {
    $cast = new ByteHumanReadable;
    $model = new class extends Model {};

    expect(fn () => $cast->set($model, 'bytes', 'invalid', []))->toThrow(InvalidArgumentException::class);
});
