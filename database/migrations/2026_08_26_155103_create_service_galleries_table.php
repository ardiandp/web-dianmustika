<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('image');
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('service_id');
            $table->index(['service_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_galleries');
    }
};
