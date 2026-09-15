<?php

namespace App\UI\Support\Scramble;

use App\Http\Resources\Support\SuccessResource;
use Dedoc\Scramble\Extensions\TypeToSchemaExtension;
use Dedoc\Scramble\Support\Generator\Types\UnknownType;
use Dedoc\Scramble\Support\Type\Generic;
use Dedoc\Scramble\Support\Type\ObjectType;
use Dedoc\Scramble\Support\Type\Type;
use Dedoc\Scramble\Support\Generator\Types\ObjectType as OpenApiObjectType;
use Dedoc\Scramble\Support\Generator\Types\StringType;

class SuccessResourceExtension extends TypeToSchemaExtension
{
    public function shouldHandle(Type $type): bool
    {
        return $type instanceof ObjectType && $type->isInstanceOf(SuccessResource::class);
    }

    public function toSchema(Type $type): OpenApiObjectType
    {
        // Fallback if generic evaluation misses the type parameter
        $templateType = new UnknownType();

        // Scramble's dynamic generic tracking evaluation
        if ($type instanceof Generic && isset($type->templateTypes[0])) {
            $templateType = $type->templateTypes[0];
        }

        return (new OpenApiObjectType())
            ->addProperty('message', (new StringType())->setDescription('API execution message.'))
            ->addProperty('data', $this->openApiTransformer->transform($templateType))
            ->setDescription('Success message with its data.')
            ->addRequired(['message', 'data']);
    }
}
