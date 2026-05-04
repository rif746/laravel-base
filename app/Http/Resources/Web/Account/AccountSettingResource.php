<?php

namespace App\Http\Resources\Web\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'key' => $this->key,
            'val' => (string) $this->val,
            'label' => $this->label,
            'options' => $this->options,
        ];
    }
}
