<?php

namespace Tests\Unit\Domains\Identity\Exports;

use App\Domains\Account\Enums\GenderOption;
use App\Domains\Account\Models\Profile;
use App\Domains\Identity\Exports\UserExport;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_maps_user_data_correctly(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $profile = new Profile([
            'gender' => GenderOption::MALE,
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234567890',
        ]);
        $user->profile()->save($profile);

        $export = new UserExport;
        $mapped = $export->map($user);

        $this->assertEquals('=ROW()-1', $mapped[0]);
        $this->assertEquals('John Doe', $mapped[1]);
        $this->assertEquals('john@example.com', $mapped[2]);
        $this->assertEquals($profile->gender->label(), $mapped[3]);
        $this->assertEquals('1990-01-01', $mapped[4]->format('Y-m-d'));
        $this->assertEquals('1234567890', $mapped[5]);
    }
}
