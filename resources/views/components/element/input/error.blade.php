@props(['name' => ''])

@error($name)
    <span {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400']) }}>
        {{ $message }}
    </span>
@enderror
