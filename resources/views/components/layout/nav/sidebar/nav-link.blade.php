@props(['route' => null, 'icon' => '', 'text' => ''])

<li>
    <a @class(['nav-link', 'active' => url()->current() == $route]) href="{{ $route }}">
        @svg($icon, ['width' => 20])
        <span class="nav-text">{{ $text }}</span>
    </a>
</li>
