<?php

namespace App\Http\Controllers\Web\Identity;

use App\Attributes\Seo;
use App\DataTables\Identity\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\Identity\User;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[Authorize('viewAny', User::class)]
    #[Seo(
        title: 'domains/identity.seo.user.title',
        description: 'domains/identity.seo.user.description',
        keywords: 'domains/identity.seo.user.keywords'
    )]
    public function __invoke(UserDataTable $userDataTable)
    {

        return $userDataTable->render('pages.identity.users.index');
    }
}
