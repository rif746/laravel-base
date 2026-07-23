<?php

namespace App\Livewire\Concerns;

use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @mixin Component
 *
 * @property string $mode
 * @property string $resourceName
 */
trait WithModal
{
    public function show(int|string $id): void
    {
        // do nothing
    }

    abstract public function hide(): void;

    #[Computed]
    public function title(): string
    {
        $resource = __('resources.'.$this->resourceName);

        return __('ui/title.'.$this->mode, ['resource' => $resource]);
    }

    #[Computed]
    public function message(): string
    {
        $resource = __('resources.'.$this->resourceName);

        return match ($this->mode) {
            'create' => __('ui/crud.success.created', ['resource' => $resource]),
            'update' => __('ui/crud.success.updated', ['resource' => $resource]),
            default => __('ui/crud.success.deleted', ['resource' => $resource]),
        };
    }
}
