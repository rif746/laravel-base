<?php

use App\Domains\System\Queries\GetModelAuditLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Tests\TestCase;

uses(TestCase::class);

test('it returns audit logs for a model', function () {
    $model = Mockery::mock(Model::class);
    $audits = Mockery::mock(MorphMany::class);

    $model->shouldReceive('audits')->once()->andReturn($audits);
    $audits->shouldReceive('with')->once()->with('user')->andReturnSelf();
    $audits->shouldReceive('latest')->once()->andReturnSelf();
    $audits->shouldReceive('limit')->once()->with(5)->andReturnSelf();
    $audits->shouldReceive('get')->once()->andReturn(new Collection);

    $query = new GetModelAuditLog;
    $result = $query->get($model);

    expect($result)->toBeInstanceOf(Collection::class);
});
