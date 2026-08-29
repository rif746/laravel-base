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
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->string('user_agent');
            $table->string('event');
            $table->string('ip_address');
            $table->longText('payload');
            $table->foreignId('user_id');
            $table->timestamp('event_time');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_login_at');
            $table->dropColumn('last_login_ip');
        });
    }
};
