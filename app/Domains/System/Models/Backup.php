<?php

namespace App\Domains\System\Models;

use App\Domains\System\Casts\ByteHumanReadable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['file_name', 'disk', 'path', 'size', 'type'])]
#[WithoutTimestamps]
class Backup extends Model
{
    protected $casts = [
        'size' => ByteHumanReadable::class,
    ];
}
