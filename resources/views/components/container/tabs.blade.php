@props(['menu' => []])

@php
    $navItemId = [];
    $contents = [];
    foreach ($menu as $i => $v) {
        $navItemId[$i] = md5(microtime());
        $contents[$i] = 'content-' . $i+1;
    }
@endphp

<ul class="nav nav-pills nav-fill my-3" id="pills-tab" role="tablist">
    @foreach ($menu as $i => $details)
        <li class="nav-item" role="presentation">
            <button @class(['nav-link', 'active' => $loop->first]) id="{{ $navItemId[$i] }}-tab" data-bs-toggle="pill"
                    data-bs-target="#{{ $navItemId[$i] }}" type="button" role="tab"
                    aria-controls="{{ $navItemId[$i] }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                {{ $details }}
            </button>
        </li>
    @endforeach
</ul>
<div class="tab-content" id="pills-tabContent">
    @foreach ($contents as $content)
        <div @class(['tab-pane fade', 'show active' => $loop->first]) id="{{ $navItemId[$loop->index] }}" role="tabpanel"
             aria-labelledby="{{ $navItemId[$i] }}-tab">
            {!! ${str($content)->camel()} !!}
        </div>
    @endforeach
</div>
