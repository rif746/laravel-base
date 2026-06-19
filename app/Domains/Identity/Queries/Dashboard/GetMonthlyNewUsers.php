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

        /** @var object{this_month: int, last_month: int} $stats */
        $stats = User::query()
            // 1. Count users who joined this exact month and year
            ->selectRaw('SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month', [
                $now->month,
                $now->year
            ])
            // 2. Count users who joined last month and year
            ->selectRaw('SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as last_month', [
                $lastMonth->month,
                $lastMonth->year
            ])
            // 3. Performance Optimization: Ignore any rows older than the start of last month
            ->where('created_at', '>=', $lastMonth->startOfMonth())
            ->first();

        $newUser = (int) $stats->this_month;
        $newUserLastMonth = (int) $stats->last_month;

        if($newUserLastMonth > 0) {
            $growthRate = ($newUser - $newUserLastMonth)/$newUserLastMonth;
            $growthRate = $growthRate * 100;
            $growthRate = number_format($growthRate, 2, '.', '');
            if($growthRate > 0) {
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
