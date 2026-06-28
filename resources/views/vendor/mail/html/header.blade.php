@props(['url'])
@use(App\Domains\System\Queries\GetSystemSettings)
@use(App\Domains\System\Enums\SystemSettingKey)
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
@php($logo = app(GetSystemSettings::class)->get(SystemSettingKey::WEB_LOGO))
@php($logo = $logo ?: 'images/logo.svg')
<img src="{{ asset_static($logo) }}" class="logo" alt="Laravel Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
