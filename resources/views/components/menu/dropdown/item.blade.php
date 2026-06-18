@props([
    'label' => '',
    'icon' => '',
    'action' => 'javascript:void(0)',
    'iconProperty' => []
])

<li>
    <a {{ $attributes->merge(['class' => 'dropdown-item']) }} href="{{ $action }}">
        @if($icon)
            @svg($icon, $iconProperty)
        @endif
        {{ __($label) }}
    </a>
</li>
