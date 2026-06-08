<?php

namespace App\Domains\System\Queries;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GetModelAuditLog
{
    public function get(Model $model): Collection
    {
        return $model->audits()
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();
    }
}
