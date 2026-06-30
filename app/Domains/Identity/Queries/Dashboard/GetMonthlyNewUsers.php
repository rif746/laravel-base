<?php

namespace App\Domains\Identity\Queries\Dashboard;

use App\Domains\Identity\Models\User;

class GetMonthlyNewUsers
{
    /**
     * @return array{total_users: int, growth_rate: string}
     */
    public function fetch(): array
    {
        $now = now();
        $lastMonth = now()->subMonth();

        $newUser = User::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $newUserLastMonth = User::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        if ($newUserLastMonth > 0) {
            $growthRate = ($newUser - $newUserLastMonth) / $newUserLastMonth;
            $growthRate = $growthRate * 100;
            $growthRate = number_format($growthRate, 2, '.', '');
            if ($growthRate > 0) {
                $growthRate = "+{$growthRate}%";
            } else {
                $growthRate = "{$growthRate}%";
            }
        } else {
            $growthRate = '+100%';
        }

        return [
            'new_users' => $newUser,
            'growth_rate' => $growthRate,
        ];
    }
}
