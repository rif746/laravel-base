@props([
    'route' => null,
    'icon' => '',
    'text' => '',
    'permission' => null,
])

@if ((is_string($permission) && auth()->user()->hasPermissionTo($permission)))
    <li>
        <a wire:navigate
            href="{{ $route }}"
            class="nav-link {{ request()->url() === $route ? 'active' : '' }}"
        >
            <x-dynamic-component :component="$icon" width="20" height="20" class="ti" />
            <span class="nav-text">{{ $text }}</span>
        </a>
    </li>
@endif
