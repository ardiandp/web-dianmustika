<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class Controller
{
    protected function makeSlug(string $modelClass, string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $base = $slug;
        $i = 2;

        while ($modelClass::query()->where('slug', $slug)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    protected function uploadImage(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }

    protected function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function syncSeo(Model $model, array $data): void
    {
        $values = [
            'title' => $data['seo_title'] ?? null,
            'description' => $data['seo_description'] ?? null,
            'keywords' => $data['seo_keywords'] ?? null,
            'canonical' => $data['seo_canonical'] ?? null,
        ];

        $values = array_filter($values, fn ($value) => $value !== null && $value !== '');

        if ($values) {
            $model->seo()->updateOrCreate([], $values);
        }
    }

    protected function parseLines(string $text): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", $text))));
    }
}
