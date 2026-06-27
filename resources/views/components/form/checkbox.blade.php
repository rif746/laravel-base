@props(['label' => '', 'name' => '', 'feedback' => null, 'id'])

@php
    $name = $attributes->has('wire:model') ? $attributes->get('wire:model') : $name;
    $id = $id ?? $name;
@endphp
<div class="form-check">
    <input
        {{ $attributes->merge([
            'class' => 'form-check-input' . ($errors->has($name) ? ' is-invalid' : ''),
            'id' => $id,
        ]) }}
        type="checkbox">
    <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
    @if ($feedback)
        <span x-text="feedback?.{{ $name }}"
            x-bind:class="{ 'invalid-feedback': feedback?.{{ $name }} }"></span>
    @elseif ($errors->has($name))
        <span class="invalid-feedback">{{ $errors->first($name) }}</span>
    @endif
</div>
