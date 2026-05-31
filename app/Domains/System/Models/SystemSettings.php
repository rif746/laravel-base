<?php

namespace App\Domains\System\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value'])]
class SystemSettings extends Model
{
    public $timestamps = false;
}
