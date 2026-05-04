@props([
    'label' => '',
    'name' => '',
    'feedback' => null,
])

<div class="mb-3" @if ($attributes->has('x-show')) x-show="{{ $attributes->get('x-show') }}" x-cloak @endif>
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input
        {{ $attributes->merge([
            'class' => 'form-control',
            'name' => $name,
            'id' => $attributes->has('id') ? $attributes->get('id') : $name,
            'x-bind:class' => $feedback ? "{'is-invalid': feedback?.$name}" : false,
        ]) }} />
    @if ($feedback)
        <span x-text="feedback?.{{ $name }}"
            x-bind:class="{ 'invalid-feedback': feedback?.{{ $name }} }"></span>
    @elseif ($errors->has($name))
        <span class="invalid-feedback">{{ $errors->first($name) }}</span>
    @endif
</div>
