@props(['url'])
@use(App\Domains\System\Queries\GetSystemSettings)
@use(App\Domains\System\Enums\SystemSettingKey)
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="@logoPath" class="logo" alt="Laravel Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
