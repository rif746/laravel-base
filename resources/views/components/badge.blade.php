@props([
    'variant' => 'info',
    'label' => '',
    'rounded' => true,
])

@php
    $variants = [
        'success' => 'bg-success-subtle text-success border-success-subtle',
        'danger' => 'bg-danger-subtle text-danger border-danger-subtle',
        'warning' => 'bg-warning-subtle text-warning border-warning-subtle',
        'info' => 'bg-info-subtle text-info border-info-subtle',
        'primary' => 'bg-primary-subtle text-primary border-primary-subtle',
        'secondary' => 'bg-light text-secondary border-light',
    ];

    $classes = $variants[$variant] ?? $variants['info'];
    $roundedClass = $rounded ? 'rounded-pill' : 'rounded';
@endphp

<span {{ $attributes->merge(['class' => "badge border {$classes} {$roundedClass} px-3 py-2 fw-medium"]) }}>
    {{ $label ?: $slot }}
</span>
