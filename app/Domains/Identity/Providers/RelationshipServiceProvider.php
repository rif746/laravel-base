<?php

namespace App\Domains\Identity\Providers;

use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\UserActivity;
use App\Domains\System\Models\File;
use App\Domains\System\Support\Registry\AuditRegistry;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class RelationshipServiceProvider extends ServiceProvider
{
    /**
     * Boot cross-domain runtime relationships.
     *
     * This provider is strictly dedicated to runtime, decoupled cross-domain
     * relationship definitions. Use Model::resolveRelationUsing() here to wire
     * explicit polymorphic or foreign-key macros between models that live in
     * different domains — without introducing a hard compile-time dependency
     * between those domains.
     *
     * Do NOT add route registrations, bindings, event listeners, or any other
     * bootstrapping logic here. Keep this file focused on relationships only.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'user' => User::class,
            'user_activity' => UserActivity::class,
            'role' => Role::class,
        ]);

        File::resolveRelationUsing(
            'uploader',
            fn (File $file) => $file->belongsTo(User::class, 'uploader_id'),
        );

        // Audit Registration
        AuditRegistry::register(User::class, __('resources.user'));
        AuditRegistry::register(Role::class, __('resources.role'));
    }
}
