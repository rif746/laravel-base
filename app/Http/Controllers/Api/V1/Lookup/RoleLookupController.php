<?php

namespace App\Http\Controllers\Api\V1\Lookup;

use App\Http\Controllers\Controller;
use App\Http\Resources\LookupResource;
use App\Models\Identity\Role;
use Illuminate\Http\Request;

class RoleLookupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $roles = Role::all(['id', 'name'])
            ->map(fn ($model) => (object) [
                'value' => $model->id,
                'label' => $model->name,
            ]);

        return LookupResource::collection($roles);
    }
}
