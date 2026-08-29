<?php

namespace App\Domains\System\Actions\Integration;

use App\Domains\System\Support\Integration\DataPayloadMapper;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RunGenericImportPipeline
{
    /**
     * Execute the generic import pipeline by transforming rows, fetching existing state,
     * and delegating the persistence operation to the mapper.
     *
     * @param Collection<int, array<string, mixed>> $rows Raw payload rows from the API or spreadsheet chunk
     * @param DataPayloadMapper $mapper The domain-specific structural mapping engine
     * @throws Exception
     */
    public function execute(Collection $rows, DataPayloadMapper $mapper): void
    {
        $lookupKey = $mapper->getLookupKey();
        $modelClass = $mapper->getModelClass();

        // 1. Transform raw payload rows into domain-aligned internal data structures
        $mappedRows = $rows->map(fn ($row) => $mapper->transform($row));

        // 2. Resolve existing domain records from the database
        if (is_string($lookupKey)) {
            // Single key lookup strategy
            $lookupValues = $mappedRows->pluck($lookupKey)
                ->filter(fn ($val) => ! is_null($val) && $val !== '')
                ->map(fn ($val) => trim((string) $val))
                ->toArray();

            $existingRecords = $modelClass::query()
                ->whereIn(column: $lookupKey, values: $lookupValues)
                ->get()
                ->keyBy($lookupKey);

        } else {
            // Composite key lookup strategy (Array of keys)
            $query = $modelClass::query();

            // Construct composite OR-WHERE query conditions using mapped internal keys
            $query->where(function ($q) use ($mappedRows, $lookupKey) {
                foreach ($mappedRows as $mappedRow) {
                    $q->orWhere(function ($subQ) use ($mappedRow, $lookupKey) {
                        foreach ($lookupKey as $col) {
                            $subQ->where($col, $mappedRow[$col] ?? null);
                        }
                    });
                }
            });

            // Map retrieved database models into an in-memory composite hash index
            $existingRecords = $query->get()->keyBy(function ($item) use ($lookupKey) {
                return $this->generateCompositeIdentifier($item->toArray(), $lookupKey);
            });
        }

        // 3. Process each row and delegate execution to the domain state action
        foreach ($mappedRows as $row) {
            // Guard clause: Ensure all required lookup key attributes are populated
            if ($this->isInvalidRow($row, $lookupKey)) {
                continue;
            }

            // Derive the string identifier used to match existing records in memory
            $lookupIdentifier = is_array($lookupKey)
                ? $this->generateCompositeIdentifier($row, $lookupKey)
                : trim((string) $row[$lookupKey]);

            try {
                DB::transaction(function () use ($row, $lookupIdentifier, $mapper, $existingRecords) {
                    $existingModel = $existingRecords->get($lookupIdentifier);

                    // Delegate execution to the underlying domain action
                    $mapper->updateOrCreateDomainState(payload: $row, existingModel: $existingModel);
                });
            } catch (Throwable $e) {
                Log::error('Generic Import failure on Row processing: '.$e->getMessage(), [
                    'row' => $row,
                    'lookupIdentifier' => $lookupIdentifier,
                ]);

                throw new Exception('Failed to process row: '.$e->getMessage());
            }
        }
    }

    /**
     * Generate a deterministic string hash key for composite lookup matching.
     */
    protected function generateCompositeIdentifier(array $data, array $keys): string
    {
        $values = array_map(fn ($k) => trim((string) ($data[$k] ?? '')), $keys);

        return implode('_', $values);
    }

    /**
     * Universal guard clause to validate lookup key integrity for single and composite keys.
     */
    protected function isInvalidRow(array $row, string|array $lookupKey): bool
    {
        $keys = (array) $lookupKey;

        foreach ($keys as $key) {
            $val = $row[$key] ?? null;
            if (is_null($val) || $val === '') {
                return true; // Skip row if any required lookup key attribute is missing
            }
        }

        return false;
    }
}
