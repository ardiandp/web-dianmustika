<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500)->index();
            $table->string('url', 500);
            $table->string('title', 255)->nullable();
            $table->string('ip_hash', 64)->nullable()->index();
            $table->string('country', 100)->nullable()->index();
            $table->string('city', 100)->nullable();
            $table->string('device', 20)->nullable()->index();
            $table->string('browser', 100)->nullable();
            $table->string('os', 100)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('session_id', 100)->nullable()->index();
            $table->boolean('is_bot')->default(false);
            $table->timestamp('viewed_at')->index();
            $table->timestamps();

            $table->index(['viewed_at', 'path']);
            $table->index(['device', 'viewed_at']);
            $table->index(['country', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
