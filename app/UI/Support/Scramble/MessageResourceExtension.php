<?php

namespace App\UI\Support\Scramble;

use App\Http\Resources\Support\MessageResource;
use Dedoc\Scramble\Extensions\TypeToSchemaExtension;
use Dedoc\Scramble\Support\Generator\Types\ObjectType as OpenApiObjectType;
use Dedoc\Scramble\Support\Generator\Types\StringType;
use Dedoc\Scramble\Support\Generator\Types\UnknownType;
use Dedoc\Scramble\Support\Type\Generic;
use Dedoc\Scramble\Support\Type\ObjectType;
use Dedoc\Scramble\Support\Type\Type;

class MessageResourceExtension extends TypeToSchemaExtension
{
    public function shouldHandle(Type $type): bool
    {
        return $type instanceof ObjectType && $type->isInstanceOf(MessageResource::class);
    }

    public function toSchema(Type $type): OpenApiObjectType
    {
        return (new OpenApiObjectType())
            ->addProperty('message', (new StringType())->setDescription('API execution message.'))
            ->setDescription('Success message with its data.')
            ->addRequired(['message']);
    }
}
