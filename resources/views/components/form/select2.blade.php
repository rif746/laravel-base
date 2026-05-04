@props([
    'label' => '',
    'name' => '',
    'url' => '',
    'feedback' => null,
])

<div x-data="select2('{{ $url }}')">
    <select x-ref="select2" class="form-control"></select>
</div>

<div x-data="select2" class="mb-3"
    @if ($attributes->has('x-show')) x-show="{{ $attributes->get('x-show') }}" x-cloak @endif>
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <select
        {{ $attributes->merge([
            'class' => 'form-select',
            'name' => $name,
            'x-ref' => 'select2',
            'id' => $attributes->has('id') ? $attributes->get('id') : $name,
            'x-bind:class' => $feedback ? "{'is-invalid': feedback?.$name}" : false,
        ]) }}>
        <option value="" disabled>{{ $label }}</option>
    </select>
    @if ($feedback)
        <span x-text="feedback?.{{ $name }}"
            x-bind:class="{ 'invalid-feedback': feedback?.{{ $name }} }"></span>
    @elseif ($errors->has($name))
        <span class="invalid-feedback">{{ $errors->first($name) }}</span>
    @endif
</div>
