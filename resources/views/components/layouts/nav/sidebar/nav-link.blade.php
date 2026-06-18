@props([
    'route' => null,
    'icon' => '',
    'text' => '',
    'permission' => null
])

@if((is_string($permission) && auth()->user()->hasPermissionTo($permission)))
    <li>
        <x-link :href="$route" :label="$text" :icon="$icon" @class(['nav-link', 'active' => url()->current() == $route]) />
    </li>
@endif
