<?php

namespace App\Domains\Account\Models;

use App\Domains\Account\Enums\GenderOption;
use App\Domains\Identity\Models\User;
use Database\Factories\Account\ProfileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'gender', 'date_of_birth', 'phone_number'])]
#[UseFactory(ProfileFactory::class)]
class Profile extends Model
{
    use HasFactory;

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
