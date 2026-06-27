<?php

namespace App\Domains\Identity\Queries\Dashboard;

use App\Domains\Identity\Models\User;

class GetTotalUsers
{
    /**
     * @return array{total_users: int, growth_rate: string}
     */
    public function fetch(): array
    {
        $now = now();
        $lastMonth = now()->subMonth();

        /** @var object{total_users: int, last_month: int} $stats */
        $stats = User::query()
            // 1. Count total users
            ->selectRaw('COUNT(id) as total_users')
            // 2. Count users who joined last month and year
            ->selectRaw('SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as last_month', [
                $lastMonth->month,
                $lastMonth->year,
            ])
            ->first();

        $currentTotalUsers = $stats->total_users;
        $totalUsersMonthAgo = $stats->last_month;

        if ($totalUsersMonthAgo > 0) {
            $growthRate = ($currentTotalUsers - $totalUsersMonthAgo) / $totalUsersMonthAgo;
            $growthRate = $growthRate * 100;
            $growthRate = number_format($growthRate, 2, '.', '');
            $growthRate = "+{$growthRate}%";
        } else {
            $growthRate = '+100%';
        }

        return [
            'total_users' => $currentTotalUsers,
            'growth_rate' => $growthRate,
        ];
    }
}
