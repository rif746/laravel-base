<?php

namespace App\Http\Resources\Web\Identity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->has('is_edit')) {
            $permissions = PermissionResource::collection($this->whenLoaded('permissions'))->pluck('name');
        } else {
            $permissions = PermissionResource::collection($this->whenLoaded('permissions'));
        }

        return [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'permissions' => $permissions,
        ];
    }
}
