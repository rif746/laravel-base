<?php

namespace App\Domains\System\Actions\Auditing;

use App\Domains\System\Models\Audit;
use App\Domains\System\Support\Registry\AuditRegistry;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

class RestoreAuditedResource
{
    /**
     * Execute the restoration of a main model and its optionally specified relations.
     *
     * @param Audit $audit The audit record containing target values
     * @param array<int, string>|null $targetRelations Specific relations to restore, or null for all
     * @throws Throwable
     */
    public function execute(Audit $audit, ?array $targetRelations = null): void
    {
        DB::transaction(function () use ($audit, $targetRelations) {
            $modelClass = $audit->auditable_type;

            if (! class_exists($modelClass)) {
                $modelClass = Relation::getMorphedModel($audit->auditable_type) ?? $audit->auditable_type;
            }

            if (! class_exists($modelClass)) {
                throw new InvalidArgumentException("Class [{$audit->auditable_type}] does not exist.");
            }

            $restorePayload = $audit->old_values ?? [];

            if (empty($restorePayload)) {
                return;
            }

            // Fetch target model record (including soft-deleted rows if trait is present)
            /** @var Model|null $model */
            $model = method_exists($modelClass, 'withTrashed')
                ? $modelClass::withTrashed()->find($audit->auditable_id)
                : $modelClass::find($audit->auditable_id);

            // Get registered relations for this model to isolate relation payloads
            $configuredRelations = AuditRegistry::getRelationsFor($modelClass);

            // 1. Separate main model attributes from relation payloads
            $mainAttributes = [];
            $relationPayloads = [];

            foreach ($restorePayload as $key => $value) {
                if (in_array($key, $configuredRelations, true)) {
                    $relationPayloads[$key] = $value;
                } else {
                    $mainAttributes[$key] = $value;
                }
            }

            // 2. Fallback: Re-create model instance if hard-deleted permanently from database
            if (! $model) {
                $model = new $modelClass();
                // Retain the original primary key ID
                $model->setAttribute($model->getKeyName(), $audit->auditable_id);
            }

            // 3. Restore Main Model State
            if (! empty($mainAttributes)) {
                $this->applyAndSaveState($model, $mainAttributes);
            }

            // 4. Restore Target Relations State
            foreach ($relationPayloads as $relationName => $payload) {
                if ($targetRelations !== null && ! in_array($relationName, $targetRelations, true)) {
                    continue;
                }

                if (! method_exists($model, $relationName)) {
                    continue;
                }

                $relation = $model->{$relationName}();
                $relatedResult = $relation->getResults();

                if ($relatedResult instanceof Model) {
                    $this->applyAndSaveState($relatedResult, (array) $payload);
                } elseif ($relatedResult instanceof Collection && is_array($payload)) {
                    // Handle HasMany / BelongsToMany collection restoration
                    foreach ($payload as $singleItemPayload) {
                        if (! is_array($singleItemPayload)) {
                            continue;
                        }

                        $relatedId = $singleItemPayload['id'] ?? null;
                        if ($relatedId) {
                            $targetRelated = $relatedResult->firstWhere('id', $relatedId);
                            if ($targetRelated instanceof Model) {
                                $this->applyAndSaveState($targetRelated, $singleItemPayload);
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * Apply attribute values, restore soft deletes if trashed, and persist changes.
     *
     * @param Model $model
     * @param array<string, mixed> $attributes
     */
    protected function applyAndSaveState(Model $model, array $attributes): void
    {
        $model->fill($attributes);

        // Restore soft-deleted row if applicable
        if (method_exists($model, 'trashed') && $model->trashed()) {
            $model->restore();
        }

        $model->save();
    }
}
