<?php

namespace App\Domains\System\Providers;

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
     *
     * Example:
     *
     *   Order::resolveRelationUsing('customer', fn (Order $order) =>
     *       $order->belongsTo(Customer::class, 'customer_id')
     *   );
     */
    public function boot(): void
    {
        // Register cross-domain relationships below.
    }
}
