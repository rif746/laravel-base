<div>
    <a class="position-relative btn-icon btn-sm btn-light btn rounded-circle" data-bs-toggle="dropdown"
        aria-expanded="false" href="#" role="button">
        <x-tabler-language width="20" />
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
        <div class="list-group list-group-flush m-0">
            <button type="button" wire:click="changeLocale('en')" @class([
                'list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 py-3 px-4',
                'active' => $currentLocale === 'en',
            ])>
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-6">🇬🇧</span>
                    <span>English</span>
                </div>
                @if ($currentLocale === 'en')
                    <x-tabler-check width="16" class="text-white" />
                @endif
            </button>
            <button type="button" wire:click="changeLocale('id')" @class([
                'list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 py-3 px-4',
                'active' => $currentLocale === 'id',
            ])>
                <div class="d-flex align-items-center gap-2">
                    <span class="fs-6">🇮🇩</span>
                    <span>Bahasa Indonesia</span>
                </div>
                @if ($currentLocale === 'id')
                    <x-tabler-check width="16" class="text-white" />
                @endif
            </button>
        </div>
    </div>
</div>
