<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->text('cocok_untuk')->nullable()->after('benefits');
            $table->text('perhatian')->nullable()->after('cocok_untuk');
            $table->string('harga_label', 50)->nullable()->after('price');
            $table->string('tipe_harga', 20)->default('tetap')->after('harga_label');
            $table->string('cta_text', 100)->nullable()->default('Reservasi Sekarang')->after('perhatian');
            $table->string('cta_url', 500)->nullable()->after('cta_text');
            $table->string('video_url', 500)->nullable()->after('cta_url');
            $table->string('focus_keyword', 255)->nullable()->after('video_url');
            $table->text('secondary_keywords')->nullable()->after('focus_keyword');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'cocok_untuk',
                'perhatian',
                'harga_label',
                'tipe_harga',
                'cta_text',
                'cta_url',
                'video_url',
                'focus_keyword',
                'secondary_keywords',
            ]);
        });
    }
};
