<?php

namespace App\Http\Controllers\Web\Account;

use App\Enums\Account\UserSettingKey;
use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\Web\Account\AccountSettingResource;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{
    public function index(Request $request)
    {
        abort_if(! $request->wantsJson(), 403);

        $settings = collect(UserSettingKey::cases())
            ->map(fn ($case) => (object) [
                'key' => $case->value,
                'val' => $request->user()->settings[$case->value] ?? $case->default(),
                'label' => $case->label(),
                'options' => $case->options(),
            ]);

        return AccountSettingResource::collection($settings);
    }

    public function update(Request $request)
    {
        $validation = collect(UserSettingKey::cases())
            ->mapWithKeys(fn ($case) => [
                $case->value => $case->validation(),
            ])->toArray();
        $settings = $request->validate($validation);

        $request->user()->update(['settings' => $settings]);

        return new SuccessResource(__('ui.crud.success.updated', ['resource' => __('domains/account.pages.user_settings.title')]));
    }
}
