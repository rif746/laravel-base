<?php

namespace App\Http\Resources\Web\Identity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => __($this->description),
            'group' => __("permissions.{$this->group}.group-name"),
        ];
    }
}
