<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Traits\LogsActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class MediaController extends Controller
{
    use LogsActivity;

    public function index(Request $request): View
    {
        $query = Media::query()->latest();

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($w) use ($q) {
                $w->where('original_name', 'like', "%{$q}%")
                    ->orWhere('file_name', 'like', "%{$q}%")
                    ->orWhere('alt_text', 'like', "%{$q}%");
            });
        }

        if ($request->filled('type') && $request->string('type') !== 'all') {
            $type = $request->string('type');
            if ($type === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } else {
                $query->where('mime_type', 'like', "%{$type}%");
            }
        }

        $media = $query->paginate(24)->withQueryString();

        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpeg,png,webp,jpg,svg', 'max:5120'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $hash = hash_file('sha256', $file->getRealPath());

        // Dedup: if same hash exists, return existing
        $existing = Media::findByHash($hash);
        if ($existing) {
            // Optionally update alt/caption if provided
            if ($request->filled('alt_text') || $request->filled('caption')) {
                $existing->update([
                    'alt_text' => $request->input('alt_text', $existing->alt_text),
                    'caption' => $request->input('caption', $existing->caption),
                ]);
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['media' => $existing, 'dedup' => true]);
            }

            return redirect()->route('admin.media.index')->with('success', 'Gambar sudah ada di library, menggunakan file existing.');
        }

        $mime = $file->getMimeType();
        $originalName = $file->getClientOriginalName();
        $ext = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
        $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . substr($hash, 0, 8) . '.' . $ext;
        $filePath = 'media/' . $fileName;

        // Store original
        Storage::disk('public')->putFileAs('media', $file, $fileName);

        $fullPath = Storage::disk('public')->path($filePath);
        $size = Storage::disk('public')->size($filePath);
        $width = null;
        $height = null;
        $thumbPath = null;
        $mediumPath = null;

        // Generate thumbnails for images (not svg)
        if (str_starts_with($mime, 'image/') && $mime !== 'image/svg+xml' && file_exists($fullPath)) {
            try {
                $manager = new ImageManager(new GdDriver());
                $image = $manager->decode($fullPath);
                $width = $image->width();
                $height = $image->height();

                // Thumb 300x300 crop
                $thumbName = pathinfo($fileName, PATHINFO_FILENAME) . '-thumb-300x300.' . $ext;
                $thumbPath = 'media/thumbs/' . $thumbName;
                $thumbFull = Storage::disk('public')->path($thumbPath);
                if (! is_dir(dirname($thumbFull))) {
                    mkdir(dirname($thumbFull), 0755, true);
                }
                $thumb = $manager->decode($fullPath);
                $thumb->cover(300, 300);
                $thumb->save($thumbFull);

                // Medium 800 max width (keep ratio)
                $mediumName = pathinfo($fileName, PATHINFO_FILENAME) . '-medium-800.' . $ext;
                $mediumPath = 'media/thumbs/' . $mediumName;
                $mediumFull = Storage::disk('public')->path($mediumPath);
                $medium = $manager->decode($fullPath);
                if ($medium->width() > 800) {
                    $medium->scale(width: 800);
                }
                $medium->save($mediumFull);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $media = Media::create([
            'file_path' => $filePath,
            'file_name' => $fileName,
            'original_name' => $originalName,
            'mime_type' => $mime,
            'size' => $size,
            'width' => $width,
            'height' => $height,
            'hash' => $hash,
            'alt_text' => $request->input('alt_text'),
            'caption' => $request->input('caption'),
            'thumbnail_path' => $thumbPath,
            'medium_path' => $mediumPath,
            'uploaded_by' => auth()->id(),
        ]);

        $this->logActivity('created', $media, "Upload media \"{$originalName}\"");

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['media' => $media, 'dedup' => false], 201);
        }

        return redirect()->route('admin.media.index')->with('success', "Media \"{$originalName}\" berhasil diupload.");
    }

    public function tinymceUpload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpeg,png,webp,jpg,svg', 'max:5120'],
        ]);

        $file = $request->file('file');
        $hash = hash_file('sha256', $file->getRealPath());
        $existing = Media::findByHash($hash);
        if ($existing) {
            return response()->json(['location' => asset('storage/' . $existing->file_path)]);
        }

        $mime = $file->getMimeType();
        $originalName = $file->getClientOriginalName();
        $ext = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
        $fileName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . substr($hash, 0, 8) . '.' . $ext;
        $filePath = 'media/' . $fileName;
        Storage::disk('public')->putFileAs('media', $file, $fileName);
        $size = Storage::disk('public')->size($filePath);

        $width = null;
        $height = null;
        $thumbPath = null;
        $mediumPath = null;
        $fullPath = Storage::disk('public')->path($filePath);
        if (str_starts_with($mime, 'image/') && $mime !== 'image/svg+xml' && file_exists($fullPath)) {
            try {
                $manager = new ImageManager(new GdDriver());
                $image = $manager->decode($fullPath);
                $width = $image->width();
                $height = $image->height();
                $thumbName = pathinfo($fileName, PATHINFO_FILENAME) . '-thumb-300x300.' . $ext;
                $thumbPath = 'media/thumbs/' . $thumbName;
                $thumbFull = Storage::disk('public')->path($thumbPath);
                if (! is_dir(dirname($thumbFull))) mkdir(dirname($thumbFull), 0755, true);
                $thumb = $manager->decode($fullPath);
                $thumb->cover(300, 300);
                $thumb->save($thumbFull);
                $mediumName = pathinfo($fileName, PATHINFO_FILENAME) . '-medium-800.' . $ext;
                $mediumPath = 'media/thumbs/' . $mediumName;
                $mediumFull = Storage::disk('public')->path($mediumPath);
                $medium = $manager->decode($fullPath);
                if ($medium->width() > 800) $medium->scale(width: 800);
                $medium->save($mediumFull);
            } catch (\Throwable $e) { report($e); }
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
            'uploaded_by' => auth()->id(),
            'thumbnail_path' => $thumbPath,
            'medium_path' => $mediumPath,
        ]);

        return response()->json(['location' => asset('storage/' . $filePath)]);
    }

    public function pick(Request $request): JsonResponse
    {
        $query = Media::query()->latest();
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($w) use ($q) {
                $w->where('original_name', 'like', "%{$q}%")
                    ->orWhere('file_name', 'like', "%{$q}%");
            });
        }
        $media = $query->limit(24)->get()->map(fn (Media $m) => [
            'id' => $m->id,
            'file_path' => $m->file_path,
            'url' => asset('storage/' . $m->file_path),
            'thumb' => $m->thumbUrl(),
            'name' => $m->original_name,
            'alt_text' => $m->alt_text,
        ]);

        return response()->json($media);
    }

    public function destroy(Media $medium): RedirectResponse
    {
        // Check if file is still referenced in any table (prevent orphan delete if reused)
        $path = $medium->file_path;
        $isUsed = $this->isFileInUse($path);

        if ($isUsed) {
            // Only delete DB row, keep file
            $this->logActivity('deleted', $medium, "Hapus media DB (file tetap karena masih dipakai) \"{$medium->original_name}\"");
            $medium->delete();

            return back()->with('warning', 'Media dihapus dari library, tapi file tetap karena masih dipakai di konten.');
        }

        // Delete file + thumbs + DB
        Storage::disk('public')->delete($medium->file_path);
        if ($medium->thumbnail_path) Storage::disk('public')->delete($medium->thumbnail_path);
        if ($medium->medium_path) Storage::disk('public')->delete($medium->medium_path);

        $this->logActivity('deleted', $medium, "Hapus media \"{$medium->original_name}\"");

        $medium->delete();

        return back()->with('success', 'Media berhasil dihapus.');
    }

    private function isFileInUse(string $path): bool
    {
        // Check all tables that store image paths as string
        $tables = [
            ['services', 'image'],
            ['service_galleries', 'image'],
            ['packages', 'image'],
            ['locations', 'image'],
            ['galleries', 'image'],
            ['testimonials', 'image'],
            ['articles', 'featured_image'],
            ['settings', 'value'], // logo, favicon, hero_image stored in settings value
        ];

        foreach ($tables as [$table, $column]) {
            try {
                if (\Illuminate\Support\Facades\DB::table($table)->where($column, $path)->exists()) {
                    return true;
                }
            } catch (\Throwable $e) {
                // ignore missing table
            }
        }

        // Also check media_library itself duplicates (hash dedup ensures not, but check)
        // Check content HTML for Tinymce embeds (storage path in description/content)
        $htmlTables = [
            ['services', 'description'],
            ['articles', 'content'],
            ['packages', 'description'],
        ];
        foreach ($htmlTables as [$table, $column]) {
            try {
                if (\Illuminate\Support\Facades\DB::table($table)->where($column, 'like', "%{$path}%")->exists()) {
                    return true;
                }
            } catch (\Throwable $e) {}
        }

        return false;
    }
}
