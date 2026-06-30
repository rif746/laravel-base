<?php

namespace Tests\Unit\Domains\System\Traits\Model;

use App\Domains\System\Traits\Model\HasFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Tests\TestCase;

uses(TestCase::class);

test('it returns correct morph relation for single file', function () {
    $model = new class extends Model {
        use HasFile;
        protected $table = 'test_models';
    };

    expect($model->hasSingleFile('avatar'))->toBeInstanceOf(MorphOne::class);
});

test('it returns correct morph relation for multi file', function () {
    $model = new class extends Model {
        use HasFile;
        protected $table = 'test_models';
    };

    expect($model->hasMultiFile('documents'))->toBeInstanceOf(MorphMany::class);
});
