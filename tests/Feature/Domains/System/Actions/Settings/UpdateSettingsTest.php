<?php

use App\Domains\System\Actions\Settings\UpdateSettings;
use App\Domains\System\DTOs\SystemSetingDTO;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

test('it updates string setting', function () {
    $action = new UpdateSettings();
    $dto = new SystemSetingDTO(SystemSettingKey::WEB_NAME, 'New Name');

    $action->execute($dto);

    expect(SystemSettings::where('key', SystemSettingKey::WEB_NAME->value)->value('value'))->toBe('New Name');
});

test('it updates existing setting', function () {
    SystemSettings::create([
        'key' => SystemSettingKey::WEB_NAME->value,
        'value' => 'Old Name',
    ]);

    $action = new UpdateSettings();
    $dto = new SystemSetingDTO(SystemSettingKey::WEB_NAME, 'New Name');

    $action->execute($dto);

    expect(SystemSettings::where('key', SystemSettingKey::WEB_NAME->value)->value('value'))->toBe('New Name');
});

test('it clears cache after update', function () {
    Cache::shouldReceive('forget')->once()->with('system_settings');

    $action = new UpdateSettings();
    $dto = new SystemSetingDTO(SystemSettingKey::WEB_NAME, 'New Name');

    $action->execute($dto);
});
