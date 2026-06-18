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
            'date_of_birth' => $this->faker->dateTime('2010'),
            'phone_number' => $this->faker->e164PhoneNumber(),
        ];
    }
}
