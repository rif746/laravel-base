<?php

use App\Attributes\Seo;
use App\Concerns\Livewire\Seo\HasSeoAttributes;
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

    public function mount()
    {
        $this->form = SystemSettings::all()->pluck('value', 'key')->toArray();
    }

    #[Computed]
    public function settings()
    {
        return SystemSettingKey::section();
    }
};
