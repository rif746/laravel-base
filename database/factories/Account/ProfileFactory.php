<?php

namespace Database\Factories\Account;

use App\Domains\Account\Enums\GenderOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gender' => [GenderOption::MALE->value, GenderOption::FEMALE->value][rand(0, 1)],
            'date_of_birth' => $this->faker->date(max: '2004-01-01'),
            'phone_number' => $this->faker->randomNumber(9),
        ];
    }
}
