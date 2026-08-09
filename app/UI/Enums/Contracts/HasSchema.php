<?php

namespace App\UI\Enums\Contracts;

use App\UI\Support\Schema\BaseSchema;

interface HasSchema
{
    public function schema(): BaseSchema;
}
