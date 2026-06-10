<?php

use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use App\Http\DataTables\Identity\RoleDataTable;
use App\Http\DataTables\Identity\UserDataTable;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

test('RoleDataTable returns builder, html, columns, and dataTable instance', function () {
    $dataTable = app(RoleDataTable::class);

    // 1. query
    $query = $dataTable->query(new Role());
    expect($query)->toBeInstanceOf(Builder::class);

    // 2. columns
    $columns = $dataTable->getColumns();
    expect($columns)->toBeArray()->not->toBeEmpty();

    // 3. html
    $html = $dataTable->html();
    expect($html)->toBeInstanceOf(HtmlBuilder::class);

    // 4. dataTable
    $eloquentDataTable = $dataTable->dataTable($query);
    expect($eloquentDataTable)->toBeInstanceOf(EloquentDataTable::class);
});

test('UserDataTable returns builder, html, columns, and dataTable instance', function () {
    $dataTable = app(UserDataTable::class);

    // 1. query
    $query = $dataTable->query(new User());
    expect($query)->toBeInstanceOf(Builder::class);

    // 2. columns
    $columns = $dataTable->getColumns();
    expect($columns)->toBeArray()->not->toBeEmpty();

    // 3. html
    $html = $dataTable->html();
    expect($html)->toBeInstanceOf(HtmlBuilder::class);

    // 4. dataTable
    $eloquentDataTable = $dataTable->dataTable($query);
    expect($eloquentDataTable)->toBeInstanceOf(EloquentDataTable::class);
});
