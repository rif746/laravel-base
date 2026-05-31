<?php

use App\Actions\System\SaveSetting;
use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\DTOs\System\SystemSetingDTO;
use App\Enums\System\SystemSettingKey;
use App\Models\System\SystemSettings;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

new #[Layout('components.layouts.app')]
#[Seo(title: 'domains/system.seo.settings.title', description: 'domains/system.seo.settings.description', keywords: 'domains/system.seo.settings.keywords')]
class extends Component
{
    use HasSeoAttributes, WithFilePond;

    public array $form = [];

    public function mount(): void
    {
        if (! empty($this->settingsValue)) {
            $this->form = $this->settingsValue;
        }
    }

    #[Computed]
    public function settings(): array
    {
        return SystemSettingKey::section();
    }

    #[Computed]
    public function settingsValue(): array
    {
        return SystemSettings::all()->pluck('value', 'key')->toArray();
    }

    public function save(SystemSettingKey $key, SaveSetting $action): void
    {
        $this->validate(['form.' . $key->value => $key->validation()]);

        $action->execute(new SystemSetingDTO(
            key: $key,
            value: $this->form[$key->value],
        ));

        unset($this->settingsValue);

        $this->js("toast('" . __('ui.crud.success.updated', ['resource' => $key->label()]) . "', 'success');");
        $this->dispatch('refresh-' . $key->value);
        $this->dispatch('$refresh');
    }
};
