<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();

            // User performing the action (Polymorphic: user_type & user_id)
            $table->nullableMorphs('user');

            $table->string('event');

            // Target Auditable Model (Polymorphic: auditable_type & auditable_id)
            $table->morphs('auditable');

            // Attribute snapshots (Stored as JSON)
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Client Request Context
            $table->text('url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 1023)->nullable();

            $table->timestamps();

            // Performance Indexing
            $table->index('event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
