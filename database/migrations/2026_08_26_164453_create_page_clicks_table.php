<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500)->index();
            $table->string('url', 500);
            $table->string('element', 100)->index();
            $table->string('label', 255)->nullable();
            $table->string('ip_hash', 64)->nullable()->index();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('device', 20)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('os', 100)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->timestamp('clicked_at')->index();
            $table->timestamps();

            $table->index(['clicked_at', 'element']);
            $table->index(['clicked_at', 'path']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_clicks');
    }
};
