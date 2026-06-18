<?php

namespace App\Domains\System\Actions\Integration;

use App\Domains\System\Support\Integration\DataPayloadMapper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RunGenericImportPipeline
{
    /**
     * @param  Collection<int, array<string, mixed>>  $rows  Raw spreadsheet rows from the current chunk
     * @param  DataPayloadMapper  $mapper  The specific structural mapping engine
     * @param  class-string<Model>  $modelClass  The Eloquent model class to query against
     */
    public function execute(Collection $rows, DataPayloadMapper $mapper, string $modelClass): void
    {
        $lookupKey = $mapper->getLookupKey();

        $lookupValues = $rows->pluck($lookupKey)
            ->filter()
            ->map(fn ($val) => trim((string) $val))
            ->toArray();

        $existingRecords = $modelClass::query()
            ->whereIn(column: $lookupKey, values: $lookupValues)
            ->get()
            ->keyBy($lookupKey);

        foreach ($rows->toArray() as $row) {
            if (empty($row[$lookupKey])) {
                continue;
            }

            try {
                DB::transaction(function () use ($row, $lookupKey, $mapper, $existingRecords) {
                    $lookupValue = trim((string) $row[$lookupKey]);

                    // Match against our pre-fetched in-memory cache
                    $matchedModel = $existingRecords->get($lookupValue);

                    // Normalize data columns via the mapper specification
                    $normalizedPayload = $mapper->transform($row);

                    // Delegate execution to the underlying actions
                    $mapper->updateOrCreateDomainState(payload: $normalizedPayload, model: $matchedModel);
                });
            } catch (Throwable $e) {
                Log::error('Generic Import failure on Row processing: '.$e->getMessage(), [
                    'row' => $row,
                    'lookupKey' => $lookupKey,
                ]);
            }
        }
    }
}
