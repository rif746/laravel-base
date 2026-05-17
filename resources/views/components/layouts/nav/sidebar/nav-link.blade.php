@props(['route' => null, 'icon' => '', 'text' => ''])

<li>
    <x-link :href="$route" :label="$text" :icon="$icon" @class(['nav-link', 'active' => url()->current() == $route]) />
</li>
