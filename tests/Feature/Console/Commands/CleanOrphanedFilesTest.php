<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\CleanOrphanedFiles;
use App\Domains\System\Actions\Files\PruneOrphanedFiles;
use Mockery;
use Tests\TestCase;

test('it cleans orphaned files', function () {
    $actionMock = Mockery::mock(PruneOrphanedFiles::class);
    $actionMock->shouldReceive('execute')
        ->once()
        ->with('public', '/')
        ->andReturn([
            'db_orphans_removed' => 1,
            'disk_orphans_removed' => 2,
        ]);

    $this->instance(PruneOrphanedFiles::class, $actionMock);

    $this->artisan('system:prune-files')
        ->expectsOutput('Starting file reconciliation...')
        ->expectsOutput('Reconciliation complete.')
        ->expectsOutput('- DB Orphans Removed: 1')
        ->expectsOutput('- Disk Stranded Files Removed: 2')
        ->assertExitCode(0);
});
