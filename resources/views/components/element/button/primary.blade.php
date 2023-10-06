<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 disabled:bg-gray-600 enabled:bg-gray-800 disabled:dark:bg-gray-500 enabled:dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs enabled:text-white disabled:dark:text-gray-700 enabled:dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
