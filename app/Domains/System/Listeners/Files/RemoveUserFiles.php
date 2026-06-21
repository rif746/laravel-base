<?php

namespace App\Domains\System\Listeners\Files;

use App\Domains\Identity\Events\Governance\UserWasPurged;
use App\Domains\System\Actions\Files\RemoveModelFile;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveUserFiles implements ShouldQueue
{
    public function __construct(protected RemoveModelFile $removeModelFile)
    {
    }

    public function handle(UserWasPurged $event): void
    {
        $this->removeModelFile->execute(
            model: $event->model,
            id: $event->user_id
        );
    }
}
