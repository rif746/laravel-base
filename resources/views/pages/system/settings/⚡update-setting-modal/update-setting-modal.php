<?php

use App\Domains\System\Actions\Settings\UpdateSettings;
use App\Domains\System\DTOs\SystemSetingDTO;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use App\Livewire\Concerns\WithModal;
use App\Livewire\Concerns\WithToast;
use App\UI\Enums\InputType;
use App\UI\Support\Schemas\InputSchemaField;
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
    public ?SystemSettingKey $settingKey = null;

    #[Validate]
    public mixed $settingValue = null;

    #[Locked]
    public string $mode = 'update';

    protected $resourceName = 'system_settings';

    public function rules(): array
    {
        return [
            'settingValue' => $this->settingKey->getValidation(),
        ];
    }

    public function show(int|string $id): void
    {
        $this->settingKey = SystemSettingKey::tryFrom($id);
        $setting = SystemSettings::where('key', $id)
            ->first();
        if ($this->inputField->type->isFile()) {
            $this->settingValue = $setting->value;
        } else {
            $this->settingValue = $setting?->translated_value ?? '-';
        }
    }

    #[Computed]
    public function inputField(): ?InputSchemaField
    {
        return $this->settingKey?->getSchema();
    }

    public function save(UpdateSettings $action): void
    {
        $this->validate();

        $action->execute(new SystemSetingDTO(
            key: $this->settingKey,
            value: $this->settingValue,
        ));

        $this->success(__('ui/crud.success.updated', ['resource' => $this->settingKey->label()]));
        $this->dispatch('hide-update-setting-modal');
        $this->dispatch('setting-updated');
    }

    public function hide(): void
    {
        $this->resetValidation();
        $this->reset();
    }
};
