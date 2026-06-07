<?php

use App\Concerns\Livewire\Shared\WithModal;
use App\Concerns\Livewire\Shared\WithToast;
use App\Domains\System\Actions\Settings\UpdateSettings;
use App\Domains\System\DTOs\SystemSetingDTO;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

new class extends Component
{
    use WithFilePond;
    use WithModal;
    use WithToast;

    #[Locked]
    public string $settingKey = '';

    #[Validate]
    public mixed $settingValue = null;

    #[Locked]
    public string $mode = 'update';

    protected $resourceName = 'system_settings';

    public function rules(): array
    {
        return [
            'settingValue' => $this->settingEnum->validation(),
        ];
    }

    public function show(int|string $id): void
    {
        $this->settingKey = $id;
        $this->settingValue = SystemSettings::where('key', $id)->first()->translated_value;
    }

    #[Computed]
    public function settingEnum(): ?SystemSettingKey
    {
        return SystemSettingKey::tryFrom($this->settingKey);
    }

    public function save(UpdateSettings $action): void
    {
        $this->validate();

        $action->execute(new SystemSetingDTO(
            key: $this->settingEnum,
            value: $this->settingValue,
        ));

        $this->success(__('ui.crud.success.updated', ['resource' => $this->settingEnum->label()]));
        $this->dispatch('hide-update-setting-modal');
        $this->dispatch('setting-updated');
    }

    public function hide(): void
    {
        $this->resetValidation();
        $this->reset();
    }
};
