<?php

use App\Domains\Identity\Queries\Lookup\UserActivityLookup;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public null|int|string $userId = null;

    #[Computed]
    public function userActivities(): Collection
    {
        return UserActivityLookup::fetch($this->userId);
    }
};
?>

<x-card
    :title="__('domains/identity/widgets.user_activities.title')"
    :subtitle="__('domains/identity/widgets.user_activities.description')"
>
    @if ($this->userActivities->isEmpty())
        <div class="text-body-secondary py-4 text-center">
            <x-tabler-activity class="icon-lg mb-2 opacity-50" width="32" height="32" />
            <p class="small mb-0">{{ __('domains/identity/widgets.user_activities.empty') }}</p>
        </div>
    @else
        <div class="list-group list-group-flush border-top border-bottom">
            @foreach ($this->userActivities as $activity)
                <div class="list-group-item d-flex align-items-center justify-content-between gap-3 px-0 py-3">
                    <!-- Activity Icon & Event Details -->
                    <div class="d-flex align-items-center gap-3 overflow-hidden">
                        <!-- Event Status Icon/Badge -->
                        <div class="bg-body-tertiary flex-shrink-0 rounded p-2">
                            <x-tabler-device-laptop class="text-primary" width="20" height="20" />
                        </div>

                        <div class="d-flex flex-column text-truncate">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-body text-truncate">
                                    {{ __('domains/identity/widgets.user_activities.events.'.$activity->event) }}
                                </span>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis border-secondary-subtle rounded-pill font-monospace fs-7 border">
                                    {{ $activity->ip_address }}
                                </span>
                            </div>

                            <!-- Truncated User Agent String -->
                            <small class="text-body-secondary text-truncate fs-7" title="{{ $activity->user_agent }}">
                                {{ $activity->user_agent }}
                            </small>
                        </div>
                    </div>

                    <!-- Timestamp / Badge -->
                    <div class="flex-shrink-0 text-end">
                        <small class="text-body-secondary fs-7 d-block">
                            {{ $activity->event_time?->diffForHumans() ?? '—' }}
                        </small>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-card>
