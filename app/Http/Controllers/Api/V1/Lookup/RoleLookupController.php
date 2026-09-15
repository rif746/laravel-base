<?php

namespace App\Http\Controllers\Api\V1\Lookup;

use App\Domains\Identity\Queries\Lookup\RoleLookup;
use App\Http\Controllers\Controller;
use App\Http\Resources\Support\LookupResource;
use Illuminate\Http\Request;

/**
 * @tags Lookup Data
 */
class RoleLookupController extends Controller
{
    /**
     * Role Data
     *
     * List of Role available in system
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(Request $request)
    {
        $result = RoleLookup::fetch($request->input('search'));

        return LookupResource::collection($result);
    }
}
