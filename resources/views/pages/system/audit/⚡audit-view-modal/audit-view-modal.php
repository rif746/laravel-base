<?php

use App\Domains\System\Queries\GetModelAuditLog;
use App\Livewire\Concerns\WithModal;
use Illuminate\Support\Collection;
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
    public mixed $model;

    #[Locked]
    public string $translation;

    #[Locked]
    public string $mode = 'view';

    #[Locked]
    public string $keyName = 'id';

    protected string $resourceName = 'audit';

    public function show(int|string $id): void
    {
        $this->model_id = $id;
    }

    #[Computed]
    public function audit(): Collection
    {
        if (is_null($this->model_id)) {
            return new Collection;
        }

        return app(GetModelAuditLog::class)->get($this->modelData);
    }

    #[Computed]
    public function modelData()
    {
        return app($this->model)->where($this->keyName, $this->model_id)->first();
    }

    public function restore(Audit $audit)
    {
        $model = $this->modelData;
        $model->transitionTo($audit, true);
        $model->save();
    }

    public function hide(): void
    {
        $this->model_id = null;
    }
};
