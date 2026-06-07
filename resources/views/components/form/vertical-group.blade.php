@props([
    'name' => '',
    'label' => '',
    'feedback' => null,
])

<div {{ $attributes->merge([
    'class' => 'mb-3',
    'x-cloak' => $attributes->has('x-show')
]) }}>
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    {{ $slot }}
    @if ($feedback)
        <span x-text="feedback?.{{ $name }}"
              x-bind:class="{ 'invalid-feedback': feedback?.{{ $name }} }"></span>
    @elseif ($errors->has($name))
        <span class="invalid-feedback">{{ $errors->first($name) }}</span>
    @endif
</div>
