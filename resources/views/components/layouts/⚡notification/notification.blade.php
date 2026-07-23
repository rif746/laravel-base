<div>
    <a class="position-relative btn-icon btn-sm btn-light btn rounded-circle" data-bs-toggle="dropdown"
        aria-expanded="false" href="#" role="button">
        <x-tabler-bell width="20" />
        <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger ms-n2 top-0 mt-2">
            {{ $this->notifications->filter(fn($value, $key) => !$value->read_at)->count() }}
            <span class="visually-hidden">{{ __('ui/notification.unread') }}</span>
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
        <ul class="list-unstyled m-0 p-0 list-group">
            @forelse ($this->notifications as $notification)
                <li @class([
                    'border-bottom p-3 list-group-item',
                    'list-group-item-dark' => !$notification->read_at,
                ])>
                    <div class="d-flex gap-3">
                        <div class="flex-grow-1 small">
                            <p class="mb-0">{{ $notification->data['title'] }}</p>
                            <p class="mb-1">{{ $notification->data['message'] }}</p>
                            <div class="text-secondary">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                        @if (!$notification->read_at)
                            <x-button theme="primary" wire:click="read('{{ $notification->id }}')"
                                class="btn-icon btn-sm rounded-circle">
                                <x-tabler-check width="20" />
                            </x-button>
                        @endif
                    </div>
                </li>
            @empty
                <li class="border-bottom p-3">
                    <div class="d-flex gap-3">
                        <div class="flex-grow-1 small">
                            <p class="mb-0">{{ __('ui/notification.empty') }}</p>
                        </div>
                    </div>
                </li>
            @endforelse
            @if ($this->notifications->filter(fn($value, $key) => !$value->read_at)->count() > 1)
                <li class="px-4 py-3 text-center">
                    <x-button wire:click="readAll" theme="primary"
                        class="w-full">{{ __('ui/notification.read_all') }}</x-button>
                </li>
            @endif
        </ul>
    </div>
</div>
