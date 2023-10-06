<button
    {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 enabled:bg-red-600 disabled:bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest enabled:hover:bg-red-500 enabled:active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 enabled:dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
