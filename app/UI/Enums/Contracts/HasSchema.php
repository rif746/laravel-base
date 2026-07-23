<?php

namespace App\UI\Enums\Contracts;

use App\UI\Support\Settings\BaseSchema;

interface HasSchema
{
    public function schema(): BaseSchema;
}
