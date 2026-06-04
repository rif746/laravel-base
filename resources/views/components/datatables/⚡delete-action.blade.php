<?php

use App\Concerns\Livewire\Shared\WithToast;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    use WithToast;

    #[Locked]
    public string $model = '';

    #[Locked]
    public string $action = '';

    #[On('delete-data')]
    public function delete(int|string $id): void
    {
        try {
            if ($this->action === '') {
                app($this->model)->destroy($id);
            } else {
                $model = app($this->model)->find($id);
                app($this->action)->execute($model);
            }
            $this->dispatch('delete-data-completed');
        } catch (Exception $e) {
            $this->warning($e->getMessage());
        }
    }
};
?>

<div>
</div>
