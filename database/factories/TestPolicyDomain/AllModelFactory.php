<?php

namespace Database\Factories\TestPolicyDomain;

use App\Domains\TestPolicyDomain\Models\AllModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AllModel>
 */
class AllModelFactory extends Factory
{
    protected $model = AllModel::class;

    public function definition(): array
    {
        return [];
    }
}
