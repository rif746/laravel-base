<?php

namespace App\Domains\Identity\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'ip_address',
    'user_agent',
    'event',
    'payload',
    'event_time',
])]
#[WithoutTimestamps]
class UserActivity extends Model
{
    protected $casts = [
        'payload' => 'encrypted:array',
        'event_time' => 'immutable_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
