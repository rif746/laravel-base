@props([
    'name' => '',
    'label' => '',
    'feedback' => null,
])

<div {{ $attributes->merge([
    'class' => 'mb-3',
    'x-cloak' => $attributes->has('x-show')
]) }}>
    <div class="d-flex justify-content-between align-items-center">
    <label for="{{ $name }}" class="form-label fw-bold">{{ $label }}</label>
    @isset($action) {{ $action }} @endisset
    </div>
    {{ $slot }}
    @if ($feedback)
        <span x-text="feedback?.{{ $name }}"
              x-bind:class="{ 'invalid-feedback': feedback?.{{ $name }} }"></span>
    @elseif ($errors->has($name))
        <span class="invalid-feedback">{{ $errors->first($name) }}</span>
    @endif
</div>
