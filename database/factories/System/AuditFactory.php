<?php

namespace Database\Factories\System;

use App\Domains\System\Models\Audit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Audit>
 */
class AuditFactory extends Factory
{
    protected $model = Audit::class;

    public function definition(): array
    {
        return [];
    }
}