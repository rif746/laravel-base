<?php

use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    #[Locked]
    public string $model = '';

    #[On('delete-data')]
    public function delete(int|string $id)
    {
        $this->model::destroy($id);
        $this->dispatch('delete-data-completed');
    }
};
?>

<div>
</div>
