<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\Support\MessageResource;
use Illuminate\Http\Request;

/**
 * @tags Authentication
 */
class LogoutController extends Controller
{
    /**
     * Logout.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user('api');
        $user->currentAccessToken()->delete();

        return new MessageResource(__('ui/common.logged_out'));
    }
}
