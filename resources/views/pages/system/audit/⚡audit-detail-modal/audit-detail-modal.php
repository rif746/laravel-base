<?php

use App\Livewire\Concerns\WithModal;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use OwenIt\Auditing\Models\Audit;

new class extends Component
{
    use WithModal;

    #[Locked]
    public mixed $model_id = null;

    #[Locked]
    public string $mode = 'view';

    protected string $resourceName = 'audit';

    public function show(int|string $id): void
    {
        $this->model_id = $id;
    }

    #[Computed]
    public function audit()
    {
        return Audit::where('id', $this->model_id)->firstOrNew();
    }

    public function hide(): void
    {
        $this->reset('model_id');
    }
};
