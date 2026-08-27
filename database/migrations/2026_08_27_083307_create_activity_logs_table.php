<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name', 255)->nullable();
            $table->string('action', 20)->index(); // created, updated, deleted, login, logout, login_failed
            $table->string('loggable_type', 255)->nullable()->index();
            $table->unsignedBigInteger('loggable_id')->nullable()->index();
            $table->string('module', 100)->index();
            $table->string('description', 500);
            $table->json('changes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['module', 'action']);
            $table->index(['loggable_type', 'loggable_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
