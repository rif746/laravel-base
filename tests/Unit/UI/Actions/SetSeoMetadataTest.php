<?php

namespace Tests\Unit\UI\Actions;

use App\UI\Actions\ResolveDynamicText;
use App\UI\Actions\SetSeoMetadata;
use App\Attributes\Seo;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

test('it applies seo metadata', function () {
    $textResolver = Mockery::mock(ResolveDynamicText::class);
    $action = new SetSeoMetadata($textResolver);

    // We assume App\Attributes\Seo exists.
    $seo = new Seo(title: 'Title', description: 'Desc', keywords: ['key'], image: 'img', context: 'target');
    $viewData = ['id' => 1];
    $request = Request::create('/', 'GET');

    $textResolver->shouldReceive('execute')->times(3)->andReturnUsing(fn($value) => $value);

    $metatagsMock = Mockery::mock();
    $metatagsMock->shouldReceive('setCanonical')->once();
    $metatagsMock->shouldReceive('setKeywords')->once();

    $opengraphMock = Mockery::mock();
    $opengraphMock->shouldReceive('addProperty')->once();

    SEOTools::shouldReceive('setTitle')->once();
    SEOTools::shouldReceive('setDescription')->once();
    SEOTools::shouldReceive('metatags')->andReturn($metatagsMock);
    SEOTools::shouldReceive('addImages')->once();
    SEOTools::shouldReceive('opengraph')->andReturn($opengraphMock);

    $action->applySeo($seo, $viewData, $request);
});
