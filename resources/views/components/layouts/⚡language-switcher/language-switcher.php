<?php

use Livewire\Component;

new class extends Component
{
    public string $currentLocale;

    public function mount(): void
    {
        $this->currentLocale = app()->getLocale();
    }

    public function changeLocale(string $locale): void
    {
        if (in_array($locale, ['en', 'id'])) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
            $this->redirect(request()->header('Referer') ?? route('dashboard'), navigate: true);
        }
    }
};
