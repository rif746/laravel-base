@props([
    'title' => '',
    'icon' => null,
    'iconProperty' => [],
    'useToggle' => false
])
<div class="dropdown">
    <button {{ $attributes->merge(['class' => 'btn' . ($useToggle ? ' dropdown-toggle' : '')]) }}
            type="button" data-bs-toggle="dropdown" aria-expanded="false">
            @if($icon)
                {{ svg($icon, $iconProperty) }}
            @endif
            {{ __($title) }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        {{ $slot }}
    </ul>
</div>
