<?php

namespace App\Livewire\Concerns;

use App\Attributes\LayoutData;
use App\UI\Actions\ApplyLayoutMetadata;
use ReflectionClass;

trait HasLayoutDataAttributes
{
    /**
     * Livewire Lifecycle hook: Automatically triggers right before every single render pass.
     */
    public function renderingHasLayoutDataAttributes(ApplyLayoutMetadata $applyLayout): void
    {
        $reflection = new ReflectionClass($this);

        // Locate the #[LayoutData] attribute attached to the class definition
        $attribute = $reflection->getAttributes(LayoutData::class)[0] ?? null;

        if ($attribute) {
            /** @var LayoutData $instance */
            $instance = $attribute->newInstance();
            // Execute the processor action, passing $this (the component) as the data context
            $applyLayout->execute($instance, $this);
        }
    }
}
