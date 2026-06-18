<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Clean up generated files just in case
    @unlink(app_path('Domains/Identity/Exports/TestExport.php'));
    @unlink(app_path('Domains/Identity/Exports/TestModelExport.php'));
});

afterEach(function () {
    // Clean up generated files
    @unlink(app_path('Domains/Identity/Exports/TestExport.php'));
    @unlink(app_path('Domains/Identity/Exports/TestModelExport.php'));
});

test('domain make export command generates plain export class', function () {
    $this->artisan('domain:make export Identity TestExport')
        ->assertExitCode(0);

    $path = app_path('Domains/Identity/Exports/TestExport.php');
    expect(File::exists($path))->toBeTrue();

    $content = File::get($path);
    expect($content)
        ->toContain('namespace App\Domains\Identity\Exports;')
        ->toContain('use Maatwebsite\Excel\Concerns\FromQuery;')
        ->toContain('use Maatwebsite\Excel\Concerns\Exportable;')
        ->toContain('use Maatwebsite\Excel\Concerns\WithHeadings;')
        ->toContain('class TestExport implements FromQuery')
        ->toContain('public function query(): Builder');
});

test('domain make export command generates model export class', function () {
    $this->artisan('domain:make export Identity TestModelExport --model=User')
        ->assertExitCode(0);

    $path = app_path('Domains/Identity/Exports/TestModelExport.php');
    expect(File::exists($path))->toBeTrue();

    $content = File::get($path);
    expect($content)
        ->toContain('namespace App\Domains\Identity\Exports;')
        ->toContain('use App\Domains\Identity\Models\User;')
        ->toContain('use Maatwebsite\Excel\Concerns\FromQuery;')
        ->toContain('use Maatwebsite\Excel\Concerns\Exportable;')
        ->toContain('class TestModelExport implements FromQuery')
        ->toContain('return User::query()');
});
