<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Clean up generated files just in case
    @unlink(app_path('Domains/Identity/Exports/TestExport.php'));
    @unlink(app_path('Domains/Identity/Exports/TestModelExport.php'));
    @unlink(app_path('Domains/Identity/Imports/TestImport.php'));
    @unlink(app_path('Domains/Identity/Imports/TestModelImport.php'));
});

afterEach(function () {
    // Clean up generated files
    @unlink(app_path('Domains/Identity/Exports/TestExport.php'));
    @unlink(app_path('Domains/Identity/Exports/TestModelExport.php'));
    @unlink(app_path('Domains/Identity/Imports/TestImport.php'));
    @unlink(app_path('Domains/Identity/Imports/TestModelImport.php'));
});

test('domain make export command generates plain export class', function () {
    $this->artisan('domain:make export Identity TestExport')
        ->assertExitCode(0);

    $path = app_path('Domains/Identity/Exports/TestExport.php');
    expect(File::exists($path))->toBeTrue();

    $content = File::get($path);
    expect($content)->toContain('namespace App\Domains\Identity\Exports;')
        ->toContain('use Maatwebsite\Excel\Concerns\FromCollection;')
        ->toContain('class TestExport implements FromCollection')
        ->toContain('public function collection(): Collection')
        ->toContain('return collect();');
});

test('domain make export command generates model export class', function () {
    $this->artisan('domain:make export Identity TestModelExport --model=User')
        ->assertExitCode(0);

    $path = app_path('Domains/Identity/Exports/TestModelExport.php');
    expect(File::exists($path))->toBeTrue();

    $content = File::get($path);
    expect($content)->toContain('namespace App\Domains\Identity\Exports;')
        ->toContain('use App\Domains\Identity\Models\User;')
        ->toContain('use Maatwebsite\Excel\Concerns\FromCollection;')
        ->toContain('class TestModelExport implements FromCollection')
        ->toContain('public function collection(): Collection')
        ->toContain('return User::all();');
});

test('domain make import command generates plain import class', function () {
    $this->artisan('domain:make import Identity TestImport')
        ->assertExitCode(0);

    $path = app_path('Domains/Identity/Imports/TestImport.php');
    expect(File::exists($path))->toBeTrue();

    $content = File::get($path);
    expect($content)->toContain('namespace App\Domains\Identity\Imports;')
        ->toContain('use Maatwebsite\Excel\Concerns\ToCollection;')
        ->toContain('class TestImport implements ToCollection')
        ->toContain('public function collection(Collection $collection): void');
});

test('domain make import command generates model import class', function () {
    $this->artisan('domain:make import Identity TestModelImport --model=User')
        ->assertExitCode(0);

    $path = app_path('Domains/Identity/Imports/TestModelImport.php');
    expect(File::exists($path))->toBeTrue();

    $content = File::get($path);
    expect($content)->toContain('namespace App\Domains\Identity\Imports;')
        ->toContain('use App\Domains\Identity\Models\User;')
        ->toContain('use Maatwebsite\Excel\Concerns\ToModel;')
        ->toContain('class TestModelImport implements ToModel')
        ->toContain('public function model(array $row): Model|null')
        ->toContain('return new User(');
});
