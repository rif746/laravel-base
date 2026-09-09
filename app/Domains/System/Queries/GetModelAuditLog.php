<?php

namespace App\Domains\System\Queries;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Domains\System\Models\Audit;

class GetModelAuditLog
{
    public function get(Model $model): Collection
    {
        return Audit::where('auditable_type', $model->getMorphClass())
            ->where('auditable_id', $model->id)
            ->latest()
            ->get();
    }
}
