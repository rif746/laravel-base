<?php

namespace Database\Factories\TestDomain;

use App\Domains\TestDomain\Models\TestModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TestModel>
 */
class TestModelFactory extends Factory
{
    protected $model = TestModel::class;

    public function definition(): array
    {
        return [];
    }
}
