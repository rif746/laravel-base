<?php

namespace App\Domains\Identity\Queries\Dashboard;

use App\Domains\Identity\Models\User;

class GetUserVerificationRates
{
    /**
     * @return array{verification_rate: float|int, verified: int, unverified: int}
     */
    public function fetch(): array
    {
        /** @var object{total: int, verified: int|null} $stats */
        $stats = User::query()
            ->selectRaw('COUNT(id) as total')
            ->selectRaw('SUM(CASE WHEN email_verified_at IS NOT NULL THEN 1 ELSE 0 END) as verified')
            ->first();

        $userTotal = (int) $stats->total;
        $userVerified = (int) ($stats->verified ?? 0);
        $userUnverified = $userTotal - $userVerified;
        $verificationRate = $userTotal > 0 ? round($userVerified / $userTotal * 100) : 0;

        return [
            'verified' => $userVerified,
            'unverified' => $userUnverified,
            'verification_rate' => $verificationRate,
        ];
    }
}
