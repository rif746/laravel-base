<?php

namespace App\Http\Controllers\Web\Identity;

use App\Attributes\Seo;
use App\Domains\Identity\DataTables\RoleDataTable;
use App\Domains\Identity\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[Authorize('viewAny', Role::class)]
    #[Seo(
        title: 'domains/identity.seo.role.title',
        description: 'domains/identity.seo.role.description',
        keywords: 'domains/identity.seo.role.keywords'
    )]
    public function __invoke(RoleDataTable $dataTable)
    {
        return $dataTable->render('pages.identity.roles.index');
    }
}
