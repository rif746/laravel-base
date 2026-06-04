<?php

namespace App\Domains\System\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path',
        'mime_type',
        'size',
        'disk',
    ];

    public function morphable()
    {
        return $this->morphTo();
    }
}
