<?php

namespace App\Http\Controllers\Api\V1\Lookup;

use App\Http\Controllers\Controller;
use App\Http\Resources\Web\Identity\PermissionResource;
use App\Models\Identity\Permission;
use Illuminate\Http\Request;

class PermissionLookupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $permissions = Permission::all(['name', 'description', 'group']);

        return PermissionResource::collection($permissions);
    }
}
