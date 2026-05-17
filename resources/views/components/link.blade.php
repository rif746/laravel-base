@props([
    'href' => '',
    'label' => '',
    'theme' => null,
    'icon' => null,
    'iconConfig' => [
        'width' => 24,
        'height' => 24,
    ],
])
<a
    {{ $attributes->merge([
        'class' => $theme ? 'link-' . $theme : '',
        'href' => $href,
        'wire:navigate' => true,
    ]) }}>
    @if ($icon)
        <span>@svg($icon, $iconConfig)</span>
    @endif
    @if (is_string($label))
        <span>{{ $label }}</span>
    @else
        <span {{ $label->attributes }}>{{ $label }}</span>
    @endif
</a>
