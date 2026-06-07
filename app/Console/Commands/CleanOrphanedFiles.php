<?php

namespace App\Console\Commands;

use App\Domains\System\Actions\Files\PruneOrphanedFiles;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('system:prune-files {--directory=avatars} {--disk=public}')]
#[Description('Command description')]
class CleanOrphanedFiles extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(PruneOrphanedFiles $action): int
    {
        $this->info('Starting file reconciliation...');

        $stats = $action->execute(
            disk: $this->option('disk'),
            directory: $this->option('directory')
        );

        $this->info('Reconciliation complete.');
        $this->line("- DB Orphans Removed: {$stats['db_orphans_removed']}");
        $this->line("- Disk Stranded Files Removed: {$stats['disk_orphans_removed']}");

        return Command::SUCCESS;
    }
}
