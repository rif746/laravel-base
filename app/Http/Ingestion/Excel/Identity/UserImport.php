<?php

namespace App\Http\Ingestion\Excel\Identity;

use App\Domains\Identity\Integration\Mappers\UserDataMapper;
use App\Domains\Identity\Models\User;
use App\Domains\System\Actions\Integration\RunGenericImportPipeline;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
     * @param Collection $rows A chunk of rows defined by chunkSize()
     */
    public function collection(Collection $rows): void
    {
        // Excel specific processor reads a file chunk, then uses the generic integration pipeline
        app(RunGenericImportPipeline::class)->execute(
            rows: $rows,
            mapper: app(UserDataMapper::class),
            modelClass: User::class,
        );
    }

    public function chunkSize(): int
    {
        return 200; // Optimal balance for shared hosting memory limits
    }
}
