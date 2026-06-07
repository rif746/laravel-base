<?php

use App\Domains\Account\Actions\Profile\UpdateUserSettings;
use App\Domains\Account\Enums\UserSettingKey;
use Livewire\Component;

new class extends Component
{
    public array $settings = [];

    public array $form = [];

    public function mount(): void
    {
        $userSettings = auth('web')->user()->settings ?? collect();

        $this->settings = collect(UserSettingKey::cases())->map(function ($key) {
            return [
                'key' => $key->value,
                'label' => $key->label(),
                'type' => $key->type(),
                'options' => $key->options(),
            ];
        })->toArray();

        foreach (UserSettingKey::cases() as $key) {
            $this->form[$key->value] = $userSettings->get($key->value, $key->default());
        }
    }

    public function save(UpdateUserSettings $action): void
    {
        $rules = [];
        foreach (UserSettingKey::cases() as $key) {
            if ($key->validation()) {
                $rules["form.{$key->value}"] = $key->validation();
            }
        }

        $this->validate($rules);

        $action->execute(auth('web')->user(), $this->form);

        $this->js("toast('".__('ui.crud.success.updated', ['resource' => __('domains/account.pages.user_settings.title')])."')");
    }
};
