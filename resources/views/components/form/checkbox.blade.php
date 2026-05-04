@props(['label' => ''])
<div class="form-check">
    <input id="remember" {{ $attributes->merge(['class' => 'form-check-input']) }} type="checkbox">
    <label class="form-check-label small" for="remember">{{ $label }}</label>
</div>
