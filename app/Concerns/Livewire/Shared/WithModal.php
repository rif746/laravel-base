<?php

namespace App\Concerns\Livewire\Shared;

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
    abstract public function show(int|string $id): void;

    abstract public function hide(): void;

    #[Computed]
    public function title(): string
    {
        $resource = __('resources.'.$this->resourceName);

        return match ($this->mode) {
            'create' => __('ui.title.create', ['resource' => $resource]),
            'update' => __('ui.title.update', ['resource' => $resource]),
            default => __('ui.title.view', ['resource' => $resource]),
        };
    }

    #[Computed]
    public function message(): string
    {
        $resource = __('resources.'.$this->resourceName);

        return match ($this->mode) {
            'create' => __('ui.crud.success.created', ['resource' => $resource]),
            'update' => __('ui.crud.success.update', ['resource' => $resource]),
            default => __('ui.crud.success.deleted', ['resource' => $resource]),
        };
    }
}
