<?php

namespace App\Http\Controllers\Web\Identity;

use App\Actions\Identity\SyncUserRolesAction;
use App\Attributes\Seo;
use App\DataTables\Identity\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Identity\UserRequest;
use App\Http\Resources\Web\Identity\UserResource;
use App\Http\Resources\SuccessResource;
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
    public function index(UserDataTable $userDataTable)
    {

        return $userDataTable->render('pages.identity.users.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Authorize('store', User::class)]
    public function store(UserRequest $request, SyncUserRolesAction $syncUserRolesAction)
    {
        $user = User::create($request->validated());

        if ($request->filled('role')) {
            $syncUserRolesAction->execute($user, $request->role);
        }

        return new SuccessResource(__('ui.crud.success.created', ['resource' => __('resources.user')]));
    }

    /**
     * Display the specified resource.
     */
    #[Authorize('view', 'user')]
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Authorize('update', 'user')]
    public function update(UserRequest $request, User $user, SyncUserRolesAction $syncUserRolesAction)
    {
        $user->update($request->validated());

        if ($request->filled('role')) {
            $syncUserRolesAction->execute($user, $request->role);
        }

        return new SuccessResource(__('ui.crud.success.updated', ['resource' => __('resources.user')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Authorize('delete', 'user')]
    public function destroy(User $user)
    {
        $user->delete();

        return new SuccessResource(__('ui.crud.success.deleted', ['resource' => __('resources.user')]));
    }
}
