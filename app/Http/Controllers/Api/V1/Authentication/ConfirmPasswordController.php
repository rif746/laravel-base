<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Authentication\ConfirmPasswordRequest;
use App\Http\Resources\Support\MessageResource;
use App\Http\Resources\Support\SuccessResource;
use Illuminate\Http\Request;

/**
 * @tags Authentication
 */
class ConfirmPasswordController extends Controller
{
    /**
     * Confirm Password
     */
    public function __invoke(ConfirmPasswordRequest $request)
    {
        $request->confirm();

        return new MessageResource(__('ui/crud.success.retrieved', ['resource' => __('domains/auth/field.confirm_password.password')]));
    }
}
