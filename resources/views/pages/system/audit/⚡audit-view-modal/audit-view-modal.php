<?php

use App\Domains\System\Queries\GetModelAuditLog;
use App\Livewire\Concerns\WithModal;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

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
        $model = app($this->model)->where($this->keyName, $this->model_id)->first();

        return app(GetModelAuditLog::class)->get($model);
    }

    public function hide(): void
    {
        $this->model_id = null;
    }
};
