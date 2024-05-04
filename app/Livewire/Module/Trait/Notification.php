<?php

namespace App\Livewire\Module\Trait;

trait Notification
{
    /**
     * @param string $title
     * @param string $message
     * @param string $type (default: 'success', available: 'success'|'danger'|'info'|'error')
     */
    public function swal($title, $message, $type = 'success')
    {
        return $this->js("window.swal({icon: '{$type}', message: '{$message}', title: '{$title}'})");
    }

    /**
     * @param string $message
     * @param string $type (default: 'success', available: 'success'|'danger'|'info'|'error')
     * @param string $position (default: 'top-end', available: 'top-end'|'top-start'|'top-center'|'bottom-start'|'bottom-end'|'bottom-center')
     */
    public function toast($message, $type = 'success', $position = 'top-end')
    {
        return $this->js("window.toast({icon: '{$type}', message: '{$message}', position: '{$position}'})");
    }
}
