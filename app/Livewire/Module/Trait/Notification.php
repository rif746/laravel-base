<?php

namespace App\Livewire\Module\Trait;

trait Notification
{
    /**
     * @param string $title
     * @param string $message
     * @param string $driver (default: 'swal', available: 'swal'|'toast')
     */
    public function notify($title, $message, $driver = 'swal')
    {
        if ($driver == 'swal')
            return $this->dispatch('swal-notify', title: $title, message: $message);
        else
            return $this->dispatch('toast-notify', title: $title, message: $message);
    }

    /**
     * @param string $title
     * @param string $message
     * @param string $dispatch (dispatch event after confirm)
     */
    public function confirm($title, $message, $dispatch)
    {
        return $this->dispatch('swal-confirm', title: $title, message: $message, onaccept: $dispatch);
    }
}
