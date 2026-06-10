<?php

namespace App\Livewire\Concerns;

use App\Attributes\Seo;
use App\UI\Actions\SetSeoMetadata;
use Livewire\Component;
use ReflectionClass;

/**
 * @mixin Component
 */
trait HasSeoAttributes
{
    /**
     * Unified logic to apply SEO from PHP 8 Attributes.
     */
    public function applySeoMetadata(SetSeoMetadata $setSeo): void
    {
        $reflection = new ReflectionClass($this);

        // 1. Check Class (Livewire default) or Method (Controller default)
        $attribute = $reflection->getAttributes(Seo::class)[0]
                  ?? $reflection->getMethod('render')->getAttributes(Seo::class)[0]
                  ?? null;

        if ($attribute) {
            $seo = $attribute->newInstance();
            $viewData = method_exists($this, 'all') ? $this->all() : [];

            $setSeo->applySeo($seo, $viewData, request());
        }
    }

    /**
     * Livewire Lifecycle hook: triggers on every render.
     */
    public function rendering(SetSeoMetadata $setSeo): void
    {
        $this->applySeoMetadata($setSeo);
    }
}
