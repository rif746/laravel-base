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
        $lastMonth = now()->subMonth();

        $currentTotalUsers = User::count();
        $totalUsersLastMonth = User::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        if ($totalUsersLastMonth > 0) {
            $growthRate = ($currentTotalUsers - $totalUsersLastMonth) / $totalUsersLastMonth;
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
