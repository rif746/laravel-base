<?php

namespace App\Domains\Identity\Imports;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Domains\Identity\Models\User;

class UserImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function __construct(
        // Inject your Domain Actions here
        // private ProvisionAction $provisionAction,
        // private UpdateAction $updateAction
    ) {}

    /**
     * @param Collection $rows A chunk of rows defined by chunkSize()
     */
    public function collection(Collection $rows): void
    {
        // 1. Pre-fetch existing records for this chunk to prevent N+1 queries
        // $keys = $rows->pluck('unique_column')->filter()->toArray();
        // $existingRecords = User::whereIn('unique_column', $keys)->get()->keyBy('unique_column');

        // 2. Process rows and delegate to Domain Actions
        foreach ($rows as $row) {
            try {
                // if ($existingRecords->has($row['unique_column'])) {
                //     // UPDATE ROUTE: map to DTO and execute UpdateAction
                // } else {
                //     // INSERT ROUTE: map to DTO and execute ProvisionAction
                // }
            } catch (Exception $e) {
                // Log failure to prevent a single bad row from killing the whole chunk
                Log::error("Import failed for row: " . $e->getMessage());
            }
        }
    }

    public function chunkSize(): int
    {
        return 200; // Optimal balance for shared hosting memory limits
    }
}
