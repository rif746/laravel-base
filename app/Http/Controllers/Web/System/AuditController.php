<?php

namespace App\Http\Controllers\Web\System;

use App\Attributes\Ui\LayoutData;
use App\Attributes\Ui\Seo;
use App\Http\Controllers\Controller;
use App\Http\DataTables\System\AuditDataTable;

class AuditController extends Controller
{
    #[LayoutData(
        header: 'domains/system/seo.audit.title',
        breadcrumbs: [
            'ui/menu.dashboard' => 'dashboard',
            'domains/system/seo.audit.title' => '',
        ],
    )]
    #[Seo(
        title: 'domains/system/seo.audit.title',
        description: 'domains/system/seo.audit.description',
        keywords: 'domains/system/seo.audit.keywords'
    )]
    public function __invoke(AuditDataTable $dataTable)
    {
        return $dataTable->render('pages.system.audit.index');
    }
}
