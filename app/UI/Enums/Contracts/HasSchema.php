<?php

namespace App\UI\Enums\Contracts;

use App\UI\Support\Schemas\BaseSchema;
use App\UI\Support\Schemas\InputSchemaField;

interface HasSchema
{
    public function schema(): BaseSchema;

    public function getSchema(): array|InputSchemaField;
}
