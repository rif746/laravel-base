<?php

namespace App\Http\Controllers\Web\Identity;

use App\Actions\Identity\SyncRolePermissionsAction;
use App\Attributes\Seo;
use App\DataTables\Identity\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Identity\RoleRequest;
use App\Http\Resources\Web\Identity\RoleResource;
use App\Http\Resources\SuccessResource;
use App\Models\Identity\Role;
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
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('pages.identity.roles.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Authorize('store', Role::class)]
    public function store(RoleRequest $request, SyncRolePermissionsAction $syncRolePermissionsAction)
    {
        $role = Role::create($request->validated());

        if ($request->filled('permissions')) {
            $syncRolePermissionsAction->execute($role, $request->permissions);
        }

        return new SuccessResource(__('ui.crud.success.created', ['resource' => __('resources.role')]));
    }

    /**
     * Display the specified resource.
     */
    #[Authorize('view', 'role')]
    public function show(Role $role)
    {
        $role->load('permissions');

        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Authorize('update', 'role')]
    public function update(RoleRequest $request, Role $role, SyncRolePermissionsAction $syncRolePermissionsAction)
    {
        $role->update($request->validated());

        if ($request->filled('permissions')) {
            $syncRolePermissionsAction->execute($role, $request->permissions);
        }

        return new SuccessResource(__('ui.crud.success.updated', ['resource' => __('resources.role')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Authorize('delete', 'role')]
    public function destroy(Role $role)
    {
        $role->delete();

        return new SuccessResource(__('ui.crud.success.deleted', ['resource' => __('resources.role')]));
    }
}
