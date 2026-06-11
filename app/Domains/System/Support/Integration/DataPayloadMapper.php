<?php

namespace App\Domains\System\Support\Integration;

use Illuminate\Database\Eloquent\Model;

interface DataPayloadMapper
{
    /**
     * Define the unique column used to locate records across entry streams.
     */
    public function getLookupKey(): string;

    /**
     * Normalize raw incoming data structures into an internal domain-safe layout array
     */
    public function transform(array $rawData): array;

    /**
     * Coordinate and execute internal domain mutations using the transformed payload.
     */
    public function updateOrCreateDomainState(array $payload, ?Model $model = null): void;
}
