@props(['name' => '', 'required' => false, 'disabled' => false])

@php($name = $name ?? $attributes->wire('model')->value)

<label for="{{ $name }}" class="inline-flex items-center">
    <input id="{{ $name }}"
        {{ $attributes->merge([
            'type' => 'checkbox',
            'name' => $name,
            'required' => $required,
            'disabled' => $disabled,
            'class' =>
                'rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800',
        ]) }} />
    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
</label>
