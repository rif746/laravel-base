@props([
    'href' => 'javascript:void(0)',
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
        'wire:navigate' => !in_array($href, ['#', 'javascript:void(0)', null]),
    ]) }}>
    @if ($icon && !str_contains($icon, 'svg'))
        <span>@svg($icon, $iconConfig)</span>
    @endif
    @isset($slot)
        {{ $slot }}
    @endisset
    @if (is_string($label))
        <span>{{ $label }}</span>
    @else
        <span {{ $label->attributes }}>{{ $label }}</span>
    @endif
</a>
