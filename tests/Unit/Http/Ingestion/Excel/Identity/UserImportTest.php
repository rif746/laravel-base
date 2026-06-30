<?php

namespace Tests\Unit\Http\Ingestion\Excel\Identity;

use App\Domains\Identity\Integration\Mappers\UserDataMapper;
use App\Domains\Identity\Models\User;
use App\Domains\System\Actions\Integration\RunGenericImportPipeline;
use App\Http\Ingestion\Excel\Identity\UserImport;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

test('it executes generic import pipeline', function () {
    $pipeline = Mockery::mock(RunGenericImportPipeline::class);
    $this->app->instance(RunGenericImportPipeline::class, $pipeline);
    $this->app->instance(UserDataMapper::class, Mockery::mock(UserDataMapper::class));

    $rows = new Collection([
        ['name' => 'Test User', 'email' => 'test@example.com'],
    ]);

    $pipeline->shouldReceive('execute')
        ->once()
        ->with(
            Mockery::on(fn($r) => $r === $rows),
            Mockery::type(UserDataMapper::class),
            User::class
        );

    $import = new UserImport();
    $import->collection($rows);
});
