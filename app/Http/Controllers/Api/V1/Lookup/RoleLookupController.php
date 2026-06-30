<?php

namespace App\Http\Controllers\Api\V1\Lookup;

use App\Domains\Identity\Queries\Lookup\RoleLookup;
use App\Http\Controllers\Controller;
use App\Http\Resources\LookupResource;
use Illuminate\Http\Request;

class RoleLookupController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, RoleLookup $lookup)
    {
        $result = $lookup->fetch($request->input('search'))
            ->map(fn($res) => (object) [
                'id' => $res->name,
                'text' => $res->name,
            ]);

        return LookupResource::collection($result);
    }
}
