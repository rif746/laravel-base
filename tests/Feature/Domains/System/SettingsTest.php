<?php

use App\Attributes\Seo;
use App\Domains\System\Actions\Settings\SetSeoMetadata;
use App\Domains\System\Actions\Settings\UpdateSettings;
use App\Domains\System\DTOs\DeleteBackupDTO;
use App\Domains\System\DTOs\SystemSetingDTO;
use App\Domains\System\Enums\SystemSettingKey;
use App\Domains\System\Models\SystemSettings;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

beforeEach(function () {
    cache()->forget('system_settings');
});

test('UpdateSettings action saves setting and runs effects', function () {
    // 1. Test Select-type setting (DEFAULT_LANGUAGE: 'en')
    $dtoLanguage = new SystemSetingDTO(
        key: SystemSettingKey::DEFAULT_LANGUAGE,
        value: 'en'
    );

    $action = app(UpdateSettings::class);
    $action->execute($dtoLanguage);

    $this->assertDatabaseHas('system_settings', [
        'key' => SystemSettingKey::DEFAULT_LANGUAGE->value,
        'value' => 'en',
    ]);

    // 2. Test Text-type setting (WEB_NAME)
    $dtoName = new SystemSetingDTO(
        key: SystemSettingKey::WEB_NAME,
        value: 'Testing Web Name'
    );

    $action->execute($dtoName);

    $this->assertDatabaseHas('system_settings', [
        'key' => SystemSettingKey::WEB_NAME->value,
        'value' => 'Testing Web Name',
    ]);
});

test('SetSeoMetadata action sets static metadata', function () {
    $request = Request::create('http://localhost/test-path');

    $seo = new Seo(
        title: 'Static Page Title',
        description: 'Static Page Description',
        keywords: ['seo', 'test'],
        image: '/images/seo.png'
    );

    $action = app(SetSeoMetadata::class);
    $action->applySeo($seo, [], $request);

    expect(SEOTools::getTitle())->toContain('Static Page Title');
    expect(SEOTools::metatags()->getDescription())->toBe('Static Page Description');
    expect(SEOTools::metatags()->getCanonical())->toBe('http://localhost/test-path');
});

test('SetSeoMetadata action resolves dynamic view data and translations', function () {
    $request = Request::create('http://localhost/dynamic-path');

    // 'domains/auth.notifications.sign_in_activity.greeting' translation key in lang/en/domains/auth.php is: 'Hello :name!'
    $seo = new Seo(
        title: 'domains/auth.notifications.sign_in_activity.greeting',
        name: 'user.name'
    );

    $user = (object) ['name' => 'Alice'];

    $action = app(SetSeoMetadata::class);
    $action->applySeo($seo, ['user' => $user], $request);

    // Should resolve name to Alice, and translate the greeting to "Hello Alice!"
    expect(SEOTools::getTitle())->toContain('Hello Alice!');
});

test('SystemSettings model returns translated values correctly', function () {
    // 1. SELECT type
    $settingSelect = SystemSettings::create([
        'key' => SystemSettingKey::DEFAULT_LANGUAGE->value,
        'value' => 'id',
    ]);
    expect($settingSelect->translated_value)->toBe('Indonesian');

    // 2. TEXTLINE type
    $settingText = SystemSettings::create([
        'key' => SystemSettingKey::WEB_NAME->value,
        'value' => 'Acme Test',
    ]);
    expect($settingText->translated_value)->toBe('Acme Test');

    // 3. FILE type
    $settingFile = SystemSettings::create([
        'key' => SystemSettingKey::WEB_LOGO->value,
        'value' => 'logos/logo.png',
    ]);
    // Since asset_static is tested via config/storage mocks
    \Illuminate\Support\Facades\Storage::fake('public');
    \Illuminate\Support\Facades\Storage::disk('public')->put('logos/logo.png', 'content');
    expect($settingFile->translated_value)->toBeString();
});

test('DeleteBackupDTO can be instantiated', function () {
    $dto = new DeleteBackupDTO('my-id');
    expect($dto->id)->toBe('my-id');
});
