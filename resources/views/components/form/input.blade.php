@props([
    'label' => '',
    'name' => '',
    'feedback' => null,
    'disabled' => false
])

@php
    $name = $attributes->has('wire:model') ? $attributes->get('wire:model') : $name;
@endphp

<div class="mb-3" @if ($attributes->has('x-show')) x-show="{{ $attributes->get('x-show') }}" x-cloak @endif>
    <label for="{{ $name }}" class="form-label fw-bold">{{ $label }}</label>
    <input
        {{ $attributes->merge([
            'class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : ''),
            'name' => $name,
            'disabled' => $disabled,
            'id' => $attributes->has('id') ? $attributes->get('id') : $name,
            'placeholder' => $attributes->has('placeholder') ? $attributes->get('placeholder') : $label,
            'x-bind:class' => $feedback ? "{'is-invalid': feedback?.$name}" : false,
        ]) }} />
    @if ($feedback)
        <span x-text="feedback?.{{ $name }}"
            x-bind:class="{ 'invalid-feedback': feedback?.{{ $name }} }"></span>
    @elseif ($errors->has($name))
        <span class="invalid-feedback">{{ $errors->first($name) }}</span>
    @endif
</div>
