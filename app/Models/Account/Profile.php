<?php

namespace App\Models\Account;

use App\Enums\Identity\GenderOption;
use App\Models\Identity\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'gender', 'date_of_birth', 'phone_number'])]
class Profile extends Model
{
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
