<?php

namespace App\Livewire\Attributes;

use Artesaos\SEOTools\Facades\SEOTools;
use Livewire\Attribute as LivewireAttribute;

#[\Attribute]
class Metadata extends LivewireAttribute
{
    public function __construct(
        $title,
        $description = null,
        $properties = [],
        $images = []
    ) {
        SEOTools::setTitle(__($title));
        SEOTools::setDescription(__($description));
        SEOTools::addImages($images);
        foreach ($properties as $key => $value) {
            SEOTools::opengraph()->addProperty($key, __($value));
        }
    }
}
