<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaPruneOrphans extends Command
{
    protected $signature = 'media:prune-orphans {--force : Hapus file fisik juga tanpa konfirmasi}';

    protected $description = 'Hapus media yang tidak dipakai konten (DB + file fisik)';

    private array $sources = [
        ['services', 'image'],
        ['service_galleries', 'image'],
        ['packages', 'image'],
        ['locations', 'image'],
        ['galleries', 'image'],
        ['testimonials', 'image'],
        ['articles', 'featured_image'],
        ['settings', 'value'],
    ];

    private array $htmlSources = [
        ['services', 'description'],
        ['articles', 'content'],
        ['packages', 'description'],
    ];

    public function handle(): int
    {
        $used = collect();

        foreach ($this->sources as [$table, $column]) {
            try {
                if ($table === 'settings') {
                    $used = $used->merge(DB::table('settings')->whereIn('key', ['logo', 'favicon', 'hero_image'])->pluck('value'));
                    continue;
                }
                $used = $used->merge(DB::table($table)->whereNotNull($column)->where($column, '!=', '')->pluck($column));
            } catch (\Throwable $e) {
            }
        }

        foreach ($this->htmlSources as [$table, $column]) {
            try {
                $rows = DB::table($table)->whereNotNull($column)->where($column, '!=', '')->pluck($column);
                foreach ($rows as $content) {
                    $matches = [];
                    preg_match_all('#storage/([A-Za-z0-9_./-]+\.(?:jpg|jpeg|png|webp|svg|gif))#i', (string) $content, $matches);
                    foreach ($matches[1] ?? [] as $m) {
                        $used->push($m);
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        $usedPaths = $used->filter()->map(fn ($v) => ltrim((string) $v, '/'))->unique();

        $orphans = Media::query()->whereNotIn('file_path', $usedPaths)->get();

        if ($orphans->isEmpty()) {
            $this->info('Tidak ada media yang tidak dipakai.');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$orphans->count()} media yang tidak dipakai konten:");

        $force = $this->option('force');

        foreach ($orphans as $medium) {
            if (! $force && ! $this->confirm("Hapus \"{$medium->original_name}\" (file tetap ikut dihapus)?", false)) {
                continue;
            }

            $disk = Storage::disk('public');
            $disk->delete($medium->file_path);
            if ($medium->thumbnail_path) {
                $disk->delete($medium->thumbnail_path);
            }
            if ($medium->medium_path) {
                $disk->delete($medium->medium_path);
            }

            $medium->delete();
            $this->info("Dihapus: {$medium->original_name}");
        }

        return self::SUCCESS;
    }
}
