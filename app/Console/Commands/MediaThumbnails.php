<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class MediaThumbnails extends Command
{
    protected $signature = 'media:thumbnails {--force : Regenerasi semua thumbnail}';

    protected $description = 'Buat/daur ulang thumbnail 300x300 & medium 800 untuk semua media gambar';

    public function handle(): int
    {
        $this->info('Memproses thumbnail media...');

        $media = Media::query()->where('mime_type', 'like', 'image/%')
            ->where('mime_type', '!=', 'image/svg+xml')
            ->get();

        if ($media->isEmpty()) {
            $this->info('Tidak ada gambar raster untuk diproses.');
            return self::SUCCESS;
        }

        $force = $this->option('force');
        $disk = Storage::disk('public');
        $manager = new ImageManager(new GdDriver());
        $processed = 0;
        $skipped = 0;

        foreach ($media as $m) {
            $filePath = $m->file_path;
            if (! $disk->exists($filePath)) {
                $this->warn("File tidak ada: {$filePath}");
                $skipped++;
                continue;
            }

            $existingThumb = $m->thumbnail_path && $disk->exists($m->thumbnail_path);
            if ($existingThumb && ! $force) {
                $skipped++;
                continue;
            }

            $fullPath = $disk->path($filePath);
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) ?: 'jpg';
            $base = pathinfo($filePath, PATHINFO_FILENAME);

            try {
                $image = $manager->decode($fullPath);
                $width = $image->width();
                $height = $image->height();

                $thumbPath = 'media/thumbs/' . $base . '-thumb-300x300.' . $ext;
                $thumbFull = $disk->path($thumbPath);
                if (! is_dir(dirname($thumbFull))) {
                    mkdir(dirname($thumbFull), 0755, true);
                }
                $manager->decode($fullPath)->cover(300, 300)->save($thumbFull);

                $mediumPath = 'media/thumbs/' . $base . '-medium-800.' . $ext;
                $mediumFull = $disk->path($mediumPath);
                if (! is_dir(dirname($mediumFull))) {
                    mkdir(dirname($mediumFull), 0755, true);
                }
                $medium = $manager->decode($fullPath);
                if ($medium->width() > 800) {
                    $medium->scale(width: 800);
                }
                $medium->save($mediumFull);

                $m->update([
                    'width' => $width,
                    'height' => $height,
                    'thumbnail_path' => $thumbPath,
                    'medium_path' => $mediumPath,
                ]);

                $this->info("OK: {$filePath}");
                $processed++;
            } catch (\Throwable $e) {
                $this->error("Gagal {$filePath}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("Selesai. Diproses: {$processed}, dilewati: {$skipped}.");

        return self::SUCCESS;
    }
}
