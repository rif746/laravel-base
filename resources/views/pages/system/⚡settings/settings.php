<?php

use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\Enums\System\SystemSettingKey;
use App\Models\System\SystemSettings;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\LivewireFilepond\WithFilePond;

new #[Layout('components.layouts.app')]
#[Seo(title: 'domains/system.seo.settings.title', description: 'domains/system.seo.settings.description', keywords: 'domains/system.seo.settings.keywords')]
class extends Component
{
    use HasSeoAttributes, WithFilePond;

    public array $form = [];

    public function mount()
    {
        $this->form = $this->settingsValue;
    }

    #[Computed]
    public function settings()
    {
        return SystemSettingKey::section();
    }

    #[Computed]
    public function settingsValue()
    {
        return SystemSettings::all()->pluck('value', 'key')->toArray();
    }

    public function save(SystemSettingKey $key)
    {
        $this->validate(['form.' . $key->value => $key->validation()]);

        $value = $this->form[$key->value];

        if ($key->isImage()) {
            if ($this->settingsValue[$key->value]) {
                remove_file($this->settingsValue[$key->value]);
            }

            if ($value instanceof TemporaryUploadedFile) {
                $value = $value->store('/system/settings/' . $key->value);
            }
        }

        SystemSettings::updateOrCreate(
            ['key' => $key->value],
            ['value' => $value],
        );

        $key->effect($key->value, $this->form[$key->value]);
        cache()->forget('system-settings');
        unset($this->settingsValue);

        $this->js("toast('" . __('ui.crud.success.updated', ['resource' => $key->label()]) . "', 'success');");
        $this->dispatch('refresh-' . $key->value);
        $this->dispatch('$refresh');
    }
};
