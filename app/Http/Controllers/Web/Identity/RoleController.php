<?php

namespace App\Http\Controllers\Web\Identity;

use App\Attributes\LayoutData;
use App\Attributes\Seo;
use App\Http\Controllers\Controller;
use App\Http\DataTables\Identity\RoleDataTable;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[LayoutData(
        header: 'domains/identity/seo.role.title',
        breadcrumbs: [
            'ui.menu.dashboard' => 'dashboard',
            'domains/identity/seo.role.title' => '',
        ],
    )]
    #[Seo(
        title: 'domains/identity/seo.role.title',
        description: 'domains/identity/seo.role.description',
        keywords: 'domains/identity/seo.role.keywords'
    )]
    public function __invoke(RoleDataTable $dataTable)
    {
        return $dataTable->render('pages.identity.roles.index');
    }
}
