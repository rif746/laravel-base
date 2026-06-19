<?php

namespace App\Http\Controllers\Web\Account;

use App\Attributes\LayoutData;
use App\Attributes\Seo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    #[Seo(
        title: 'domains/account/seo.profile.title',
        description: 'domains/account/seo.profile.description',
        keywords: 'domains/account/seo.profile.keywords'
    )]
    #[LayoutData(
        header: 'domains/account/seo.profile.title',
        breadcrumbs: [
            'ui.menu.dashboard' => 'dashboard',
            'domains/account/seo.profile.title' => '',
        ],
    )]
    public function __invoke(Request $request)
    {
        return view('pages.account.profile.index');
    }
}
