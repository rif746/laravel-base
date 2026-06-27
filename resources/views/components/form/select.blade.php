@props([
    'label' => null,
    'name' => '',
    'feedback' => null,
    'options' => [],
    'noLabel' => false,
])

@php
    $name = $attributes->has('wire:model') ? $attributes->get('wire:model') : $name;
@endphp

<div @if($attributes->has('x-select2')) wire:ignore @endif class="form-group" @if ($attributes->has('x-show')) x-show="{{ $attributes->get('x-show') }}" x-cloak @endif>
    @if(!$noLabel)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif
    <select
        {{ $attributes->merge([
            'class' => 'form-select' . ($errors->has($name) ? ' is-invalid' : ''),
            'name' => $name,
            'id' => $attributes->has('id') ? $attributes->get('id') : $name,
            'x-bind:class' => $feedback ? "{'is-invalid': feedback?.$name}" : false,
        ]) }}>
        <option value="">{{ $label }}</option>
        @if(!empty($options))
            @foreach($options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        @else
            {{ $slot ?? '' }}
        @endif
    </select>
    @if ($feedback)
        <span x-text="feedback?.{{ $name }}"
            x-bind:class="{ 'invalid-feedback': feedback?.{{ $name }} }"></span>
    @elseif ($errors->has($name))
        <span class="invalid-feedback">{{ $errors->first($name) }}</span>
    @endif
</div>
