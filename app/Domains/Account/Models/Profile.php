<?php

namespace App\Domains\Account\Models;

use App\Domains\Account\Enums\GenderOption;
use App\Domains\Identity\Models\User;
use Database\Factories\Account\ProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'gender', 'date_of_birth', 'phone_number'])]
class Profile extends Model
{
    /** @use HasFactory<ProfileFactory> */
    use HasFactory;

    protected static function newFactory(): Factory
    {
        return ProfileFactory::new();
    }

    protected function casts(): array
    {
        return [
            'gender' => GenderOption::class,
            'date_of_birth' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
