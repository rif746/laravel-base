<?php

namespace App\Domains\System\Observers;

use App\Domains\System\Models\Audit;
use App\Domains\System\Support\Registry\AuditRegistry;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    /**
     * Handle the Eloquent 'created' event.
     */
    public function created(Model $model): void
    {
        $this->recordAudit($model, 'created');
    }

    /**
     * Handle the Eloquent 'updated' event.
     */
    public function updated(Model $model): void
    {
        $this->recordAudit($model, 'updated');
    }

    /**
     * Handle the Eloquent 'deleted' event.
     */
    public function deleted(Model $model): void
    {
        $this->recordAudit($model, 'deleted');
    }

    /**
     * Handle the Eloquent 'restored' event.
     */
    public function restored(Model $model): void
    {
        $this->recordAudit($model, 'restored');
    }

    /**
     * Build and persist the audit record using AuditRegistry configuration.
     */
    protected function recordAudit(Model $model, string $event): void
    {
        $registeredModels = AuditRegistry::getRegisteredModels();
        $morphClass = $model->getMorphClass();

        // Check if the model or its morph alias is registered in AuditRegistry
        $config = $registeredModels[$morphClass] ?? $registeredModels[get_class($model)] ?? null;

        if (! $config) {
            return;
        }

        // 1. Validate if current event is allowed for auditing
        if (! empty($config['events']) && ! in_array($event, $config['events'], true)) {
            return;
        }

        $relations = $config['relations'] ?? [];

        // Preload configured relations to avoid missing data or N+1 queries
        if (! empty($relations)) {
            $model->loadMissing($relations);
        }

        $oldValues = [];
        $newValues = [];

        // 2. Handle 'created' event: Capture raw model attributes merged with original attributes
        if ($event === 'created') {
            $rawAttributes = array_merge($model->getOriginal(), $model->getAttributes());
            $newValues = $this->filterAttributes($rawAttributes, $config);

            foreach ($relations as $relationName) {
                $related = $model->getRelationValue($relationName);
                if ($related instanceof Model) {
                    $newValues[$relationName] = $related->getAttributes();
                }
            }
        }

        // 3. Handle 'updated' event: Capture ONLY dirty/changed attributes and matching old values
        if ($event === 'updated') {
            $dirtyAttributes = $this->filterAttributes($model->getDirty(), $config);

            // Skip recording if no tracked attributes were actually modified
            if (empty($dirtyAttributes)) {
                return;
            }

            $newValues = $dirtyAttributes;
            $oldValues = array_intersect_key($model->getOriginal(), $dirtyAttributes);
        }

        // 4. Handle 'deleted' event: Capture ALL original attributes and relation snapshots
        if ($event === 'deleted') {
            $oldValues = $this->filterAttributes($model->getOriginal(), $config);

            foreach ($relations as $relationName) {
                $related = $model->getRelationValue($relationName);
                if ($related instanceof Model) {
                    $oldValues[$relationName] = $related->getOriginal();
                }
            }
        }

        // 5. Handle 'restored' event: Capture restored attribute state
        if ($event === 'restored') {
            $newValues = $this->filterAttributes($model->getAttributes(), $config);
        }

        // 6. Guard check: Ensure we have actual values to record before persisting
        if (empty($newValues) && empty($oldValues)) {
            return;
        }

        // 7. Persist audit ledger record
        Audit::create([
            'user_type'      => auth()->check() ? get_class(auth()->user()) : null,
            'user_id'        => auth()->id(),
            'event'          => $event,
            'auditable_type' => $morphClass,
            'auditable_id'   => $model->getKey(),
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'url'            => request()->fullUrl(),
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }

    /**
     * Filter the attribute list according to 'only' or 'exclude' configuration rules.
     *
     * @param array<string, mixed> $attributes
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    protected function filterAttributes(array $attributes, array $config): array
    {
        $only = $config['only'] ?? [];
        $exclude = $config['exclude'] ?? [];

        if (! empty($only)) {
            $attributes = array_intersect_key($attributes, array_flip($only));
        }

        if (! empty($exclude)) {
            $attributes = array_diff_key($attributes, array_flip($exclude));
        }

        return $attributes;
    }
}
