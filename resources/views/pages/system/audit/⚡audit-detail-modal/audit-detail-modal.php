<?php

use App\Domains\System\Actions\Auditing\RestoreAuditedResource;
use App\Livewire\Concerns\WithModal;
use App\Livewire\Concerns\WithToast;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use App\Domains\System\Models\Audit;

new class extends Component
{
    use WithModal;
    use WithToast;

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
        return Audit::where('id', $this->model_id)->with(['user', 'auditable'])->firstOrNew();
    }

    public function hide(): void
    {
        $this->reset('model_id');
    }

    public function restore(): void
    {
        try {
            app(RestoreAuditedResource::class)->execute($this->audit);
            $this->success(__('ui/crud.success.restored', ['resource' => __('resources.'.$this->audit->auditable_type)]));
        } catch (Exception $e) {
            $this->error(__('ui/crud.error.generic'));
            dd($e);
        }
    }
};
