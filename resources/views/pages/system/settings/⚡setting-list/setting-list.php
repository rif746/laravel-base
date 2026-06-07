<?php

use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
use App\Concerns\Livewire\Shared\WithToast;
use App\Domains\System\Actions\Settings\UpdateSettings;
use App\Domains\System\DTOs\SystemSetingDTO;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

new #[Layout('components.layouts.app')]
#[Seo(title: 'domains/system.seo.settings.title', description: 'domains/system.seo.settings.description', keywords: 'domains/system.seo.settings.keywords')]
class extends Component {
    use HasSeoAttributes, WithFilePond, WithToast;

    public array $form = [];

    public function mount(): void
    {
        if (!empty($this->settingsValue)) {
            $this->form = $this->settingsValue;
        }
    }

    #[Computed]
    public function settings(): array
    {
        return SystemSettingKey::section();
    }

    #[On('setting-updated')]
    #[Computed]
    public function settingsValue(): array
    {
        return SystemSettings::all()->pluck('translated_value', 'key')->toArray();
    }
};
