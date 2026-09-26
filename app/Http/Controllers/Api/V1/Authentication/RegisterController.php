<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Domains\Identity\Actions\Onboarding\RegisterSelfServiceUser;
use App\Domains\Identity\DTOs\Onboarding\RegisterSelfServiceUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Authentication\RegisterRequest;
use App\Http\Resources\Identity\UserResource;
use App\Http\Resources\Support\SuccessResource;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * @tags Authentication
 */
class RegisterController extends Controller
{
    /**
     * Register.
     */
    public function __invoke(RegisterRequest $request, RegisterSelfServiceUser $newUser)
    {
        try {
            $provision = $newUser->execute(new RegisterSelfServiceUserDTO(
                name: $request->post('name'),
                email: $request->post('email'),
                password: $request->post('password'),
            ));

            return new SuccessResource(
                resource: new UserResource($provision),
                message: __('ui/crud.success.created', [
                    'resource' => __('resources.user')
                ])
            );
        } catch (Throwable $e){
            Log::error($e->getMessage());
            abort(500, $e->getMessage());
        }

    }
}
