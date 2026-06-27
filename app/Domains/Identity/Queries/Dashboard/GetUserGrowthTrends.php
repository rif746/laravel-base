<?php

namespace App\Domains\Identity\Queries\Dashboard;

use App\Domains\Identity\Models\User;
use Illuminate\Support\Carbon;

class GetUserGrowthTrends
{
    /**
     * @return array{categories: array<string>, series: array<int>}
     */
    public function fetch(): array
    {
        $year = now()->year;
        $records = User::query()
            ->selectRaw('MONTH(created_at) as month')
            ->selectRaw('COUNT(id) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $categories = [];
        $series = [];

        // Ensure all 12 months are populated, filling gaps with 0
        for ($i = 1; $i <= 12; $i++) {
            $categories[] = Carbon::create()->month($i)->translatedFormat('F');
            $series[] = $records[$i] ?? 0;
        }

        return [
            'categories' => $categories,
            'series' => $series,
        ];
    }
}
