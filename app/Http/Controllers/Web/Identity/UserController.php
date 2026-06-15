<?php

namespace App\Http\Controllers\Web\Identity;

use App\Attributes\LayoutData;
use App\Attributes\Seo;
use App\Domains\Identity\Models\User;
use App\Http\Controllers\Controller;
use App\Http\DataTables\Identity\UserDataTable;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[Authorize('viewAny', User::class)]
    #[LayoutData(
        header: 'domains/identity.seo.user.title',
        breadcrumbs: [
            'ui.menu.dashboard' => 'dashboard',
            'domains/identity.seo.user.title' => '',
        ],
    )]
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
