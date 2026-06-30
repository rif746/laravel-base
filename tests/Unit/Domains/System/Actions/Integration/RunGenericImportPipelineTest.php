<?php

namespace Tests\Unit\Domains\System\Actions\Integration;

use App\Domains\System\Actions\Integration\RunGenericImportPipeline;
use App\Domains\System\Support\Integration\DataPayloadMapper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it runs import pipeline', function () {
    $dummyModel = new class extends Model {
        protected $guarded = [];
        protected $table = 'dummy_models';
    };

    Schema::create('dummy_models', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('external_id')->unique();
    });

    $rows = collect([
        ['external_id' => '1', 'name' => 'Item 1'],
        ['external_id' => '2', 'name' => 'Item 2'],
    ]);

    $mapper = Mockery::mock(DataPayloadMapper::class);
    $mapper->shouldReceive('getLookupKey')->andReturn('external_id');
    $mapper->shouldReceive('transform')->twice()->andReturnUsing(fn($row) => $row);
    $mapper->shouldReceive('updateOrCreateDomainState')->twice();

    $action = new RunGenericImportPipeline();
    $action->execute($rows, $mapper, get_class($dummyModel));
});
