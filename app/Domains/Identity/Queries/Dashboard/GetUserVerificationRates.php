<?php

namespace App\Domains\Identity\Queries\Dashboard;

use App\Domains\Identity\Models\User;

class GetUserVerificationRates
{
    /**
     * @return array{verification_rate: string, verified: int, unverified: int}
     */
    public function fetch(): array
    {
        /** @var object{total: int, verified: int} $stats */
        $stats = User::query()
            ->selectRaw('COUNT(id) as total')
            ->selectRaw('SUM(CASE WHEN email_verified_at IS NOT NULL THEN 1 ELSE 0 END) as verified')
            ->first();

        $userTotal = $stats->total;
        $userVerified = $stats->verified;
        $userUnverified = $stats->total - $stats->verified;
        $verificationRate = $userTotal > 0 ? round($userVerified / $userTotal * 100) : 0;

        return [
            'verified' => $userVerified,
            'unverified' => $userUnverified,
            'verification_rate' => $verificationRate,
        ];
    }
}
