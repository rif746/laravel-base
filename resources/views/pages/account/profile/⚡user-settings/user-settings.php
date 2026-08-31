<?php

use App\Domains\Identity\Actions\IdentityMaintenance\UpdateUserSettings;
use App\Domains\Identity\Enums\UserSettingKey;
use App\Livewire\Concerns\WithToast;
use Livewire\Component;

new class extends Component
{
    use WithToast;

    public array $settings = [];

    public array $form = [];

    public function mount(): void
    {
        $userSettings = auth('web')->user()->settings ?? collect();

        $this->settings = collect(UserSettingKey::cases())->map(function (UserSettingKey $key) {
            $field = $key->getSchema(); // Returns InputSchemaField

            return [
                'key' => $field->key,
                'label' => $field->label,
                'type' => $field->type,
                'options' => $field->attributes['options'] ?? [],
            ];
        })->toArray();

        foreach (UserSettingKey::cases() as $key) {
            $field = $key->getSchema();
            $this->form[$field->key] = $userSettings->get($field->key, $field->default);
        }
    }

    public function save(UpdateUserSettings $action): void
    {
        $rules = [];
        foreach (UserSettingKey::cases() as $key) {
            $field = $key->getSchema();
            if (! empty($field->rules)) {
                $rules["form.{$field->key}"] = $field->rules;
            }
        }

        $this->validate($rules);

        $action->execute(auth('web')->user(), $this->form);
        $this->success(__('ui/crud.success.updated', ['resource' => __('domains/account/sections.account.user_settings.title')]));
    }
};
