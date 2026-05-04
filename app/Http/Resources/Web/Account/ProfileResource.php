<?php

namespace App\Http\Resources\Web\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->relationLoaded('profile')) {
            $gender = '-';
            $phone_number = '-';
            $date_of_birth = '-';

            if ($this->profile) {
                if ($request->has('is_edit')) {
                    $gender = $this->profile->gender;
                    $phone_number = $this->profile->phone_number;
                    $date_of_birth = $this->profile->date_of_birth?->format('Y-m-d');
                } else {
                    $gender = $this->profile?->gender?->label();
                    $phone_number = $this->profile?->phone_number;
                    $date_of_birth = $this->profile?->date_of_birth?->format('d M Y');
                }
            }

            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'email_verified' => (bool) $this->email_verified_at,
                'gender' => $gender,
                'phone_number' => $phone_number,
                'date_of_birth' => $date_of_birth,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
