<?php

namespace App\Concerns\Livewire\Shared;

use Livewire\Component;

/**
 * @mixin Component
 */
trait WithToast
{
    public function success(string $message): void
    {
        $this->js("window.toast('$message', 'success')");
    }

    public function warning(string $message): void
    {
        $this->js("window.toast(\"$message\", \"warning\")");
    }

    public function error(string $message): void
    {
        $this->js("window.toast('$message', 'error')");
    }
}
