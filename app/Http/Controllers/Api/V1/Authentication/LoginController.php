<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Domains\Identity\Actions\Authentication\IssueApiToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Authentication\LoginRequest;
use App\Http\Resources\Api\V1\Authentication\LoginResponse;
use App\Http\Resources\Support\SuccessResource;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;

/**
 * @tags Authentication
 */
class LoginController extends Controller
{
    /**
     * Login.
     *
     * @return SuccessResource<LoginResponse>
     */
    public function __invoke(LoginRequest $request, IssueApiToken $issueApiToken)
    {
        $auth = $request->authenticate($issueApiToken);

        return new SuccessResource(new LoginResponse($auth));
    }
}
