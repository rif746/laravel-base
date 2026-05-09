<?php

namespace App\Http\Controllers\Web\System;

use App\Enums\System\SystemSettingKey;
use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResource;
use App\Models\System\SystemSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.system.settings.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'key' => ['required'],
            'value' => ['required'],
        ]);

        $key = SystemSettingKey::tryFrom($validated['key']);
        if (! $key) {
            throw ValidationException::withMessages(['key' => __('validation.not_in', ['attribute' => 'key'])]);
        }

        Cache::delete('system-settings');

        SystemSettings::where('key', $key->value)->update(['value' => $validated['value']]);

        return new SuccessResource(__('ui.crud.success.updated', ['resource' => __('resources.settings')]));
    }
}
