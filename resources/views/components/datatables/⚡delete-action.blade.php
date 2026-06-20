<?php

use App\Livewire\Concerns\WithToast;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    use WithToast;

    #[Locked]
    public string $model = '';

    #[Locked]
    public string $action = '';

    #[Locked]
    public string $keyName = 'id';

    #[On('delete-data')]
    public function delete(int|string $id): void
    {
        try {
            if ($this->action === '') {
                app($this->model)->where($this->keyName, $id)->delete();
            } else {
                $model = app($this->model)->where($this->keyName, $id)->first();
                app($this->action)->execute($model);
            }
            $this->dispatch('delete-data-completed');
        } catch (Exception $e) {
            $this->dispatch('delete-data-failed', message: $e->getMessage());
        }
    }
};
?>

<div>
</div>
