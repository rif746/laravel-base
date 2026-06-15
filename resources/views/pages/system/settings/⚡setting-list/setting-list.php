<?php

use App\Attributes\LayoutData;
use App\Attributes\Seo;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use App\Livewire\Concerns\HasLayoutDataAttributes;
use App\Livewire\Concerns\HasSeoAttributes;
use App\Livewire\Concerns\WithToast;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

new #[Layout('components.layouts.app')]
#[LayoutData(
    header: 'domains/system.seo.settings.title',
    breadcrumbs: [
        'ui.menu.dashboard' => 'dashboard',
        'domains/system.seo.settings.title' => '',
    ],
    context: 'user'
)]
#[Seo(
    title: 'domains/system.seo.settings.title',
    description: 'domains/system.seo.settings.description',
    keywords: 'domains/system.seo.settings.keywords'
)]
class extends Component {
    use HasSeoAttributes, HasLayoutDataAttributes, WithFilePond, WithToast;

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
