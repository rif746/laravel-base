@props(['href' => 'javascript:void(0)'])

<a {{ $attributes->merge(['class' => 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100']) }}
    href="{{ $href }}" wire:navigate>
    {{ $slot }}
</a>
