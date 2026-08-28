<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class MediaImportExisting extends Command
{
    protected $signature = 'media:import-existing';

    protected $description = 'Impor semua gambar konten yang sudah ada ke Media Library (dedup via hash + thumbnail)';

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

    public function handle(): int
    {
        $allPaths = [];

        foreach ($this->sources as [$table, $column]) {
            try {
                $rows = DB::table($table)->whereNotNull($column)->where($column, '!=', '')->pluck($column);
            } catch (\Throwable $e) {
                $this->warn("Lewati tabel {$table} (tidak ada).");
                continue;
            }

            if ($table === 'settings') {
                $allowedKeys = ['logo', 'favicon', 'hero_image'];
                $rows = DB::table('settings')->whereIn('key', $allowedKeys)->pluck('value');
            }

            $rows = collect($rows)->map(fn ($v) => is_string($v) ? $v : null)->filter();
            $allPaths = array_merge($allPaths, $rows->values()->all());
        }

        $allPaths = array_values(array_unique(array_filter($allPaths)));

        if (empty($allPaths)) {
            $this->info('Tidak ada gambar konten untuk diimpor.');
            return self::SUCCESS;
        }

        $disk = Storage::disk('public');
        $manager = new ImageManager(new GdDriver());
        $created = 0;
        $deduped = 0;
        $skipped = 0;

        foreach ($allPaths as $path) {
            $path = ltrim($path, '/');

            if (! $disk->exists($path)) {
                $this->warn("Lewati (file tidak ada): {$path}");
                $skipped++;
                continue;
            }

            if (str_starts_with(strtolower($path), 'media/')) {
                $this->line("Lewati (sudah di library): {$path}");
                $skipped++;
                continue;
            }

            $fullPath = $disk->path($path);
            $hash = hash_file('sha256', $fullPath);

            if (Media::findByHash($hash)) {
                $this->line("Dedup (hash sama): {$path}");
                $deduped++;
                continue;
            }

            $originalName = basename($path);
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION)) ?: 'jpg';
            $mime = $this->guessMime($fullPath, $ext);

            $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . substr($hash, 0, 8) . '.' . $ext;
            $filePath = 'media/' . $fileName;

            if ($disk->exists($filePath)) {
                $this->warn("Lewati (nama media sama): {$path}");
                $skipped++;
                continue;
            }

            $disk->copy($path, $filePath);
            $size = $disk->size($filePath);
            $width = null;
            $height = null;
            $thumbPath = null;
            $mediumPath = null;

            if (str_starts_with($mime, 'image/') && $mime !== 'image/svg+xml' && file_exists($disk->path($filePath))) {
                try {
                    $image = $manager->decode($disk->path($filePath));
                    $width = $image->width();
                    $height = $image->height();

                    $thumbName = pathinfo($fileName, PATHINFO_FILENAME) . '-thumb-300x300.' . $ext;
                    $thumbPath = 'media/thumbs/' . $thumbName;
                    $thumbFull = $disk->path($thumbPath);
                    if (! is_dir(dirname($thumbFull))) {
                        mkdir(dirname($thumbFull), 0755, true);
                    }
                    $manager->decode($disk->path($filePath))->cover(300, 300)->save($thumbFull);

                    $mediumName = pathinfo($fileName, PATHINFO_FILENAME) . '-medium-800.' . $ext;
                    $mediumPath = 'media/thumbs/' . $mediumName;
                    $mediumFull = $disk->path($mediumPath);
                    if (! is_dir(dirname($mediumFull))) {
                        mkdir(dirname($mediumFull), 0755, true);
                    }
                    $medium = $manager->decode($disk->path($filePath));
                    if ($medium->width() > 800) {
                        $medium->scale(width: 800);
                    }
                    $medium->save($mediumFull);
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            Media::create([
                'file_path' => $filePath,
                'file_name' => $fileName,
                'original_name' => $originalName,
                'mime_type' => $mime,
                'size' => $size,
                'width' => $width,
                'height' => $height,
                'hash' => $hash,
                'thumbnail_path' => $thumbPath,
                'medium_path' => $mediumPath,
                'uploaded_by' => null,
            ]);

            $this->info("Impor: {$path} -> {$filePath}");
            $created++;
        }

        $this->newLine();
        $this->info("Selesai. Dibuat: {$created}, dedup: {$deduped}, dilewati: {$skipped}.");

        return self::SUCCESS;
    }

    private function guessMime(string $fullPath, string $ext): string
    {
        $map = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'gif' => 'image/gif',
        ];

        return $map[strtolower($ext)] ?? (function_exists('mime_content_type') ? (mime_content_type($fullPath) ?: 'application/octet-stream') : 'application/octet-stream');
    }
}
