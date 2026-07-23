<?php

namespace Tests\Unit\UI\Support\Excel;

use App\UI\Support\Excel\StyledExport;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Mockery;

test('it delegates to the domain export', function () {
    $domainExport = Mockery::mock(FromQuery::class, WithHeadings::class, WithMapping::class, WithColumnFormatting::class);

    $domainExport->shouldReceive('query')->once()->andReturn(Mockery::mock(Builder::class));
    $domainExport->shouldReceive('headings')->once()->andReturn(['Header']);
    $domainExport->shouldReceive('map')->once()->with('row')->andReturn(['Mapped']);
    $domainExport->shouldReceive('columnFormats')->once()->andReturn(['A' => 'FORMAT']);

    $export = new StyledExport($domainExport);

    expect($export->query())->toBeInstanceOf(Builder::class)
        ->and($export->headings())->toBe(['Header'])
        ->and($export->map('row'))->toBe(['Mapped'])
        ->and($export->columnFormats())->toBe(['A' => 'FORMAT']);
});
