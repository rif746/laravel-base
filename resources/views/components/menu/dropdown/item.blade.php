@props([
    'label' => '',
    'icon' => '',
    'action' => 'javascript:void(0)',
    'iconProperty' => []
])

<li>
    <x-link {{ $attributes->merge(['class' => 'dropdown-item']) }} href="{{ $action }}" :label="$label"
            :icon="$icon" :icon-config="$iconProperty" />
</li>
