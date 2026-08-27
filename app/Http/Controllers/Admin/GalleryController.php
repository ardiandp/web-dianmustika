<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Traits\LogsActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    use LogsActivity;
    public function index(): View
    {
        $galleries = Gallery::query()
            ->orderBy('sort_order')
            ->get();

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create(): View
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'in:tempat,treatment,aktivitas,promo'],
            'image' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $gallery = Gallery::create([
            'category' => $validated['category'],
            'image' => $this->uploadImage($request->file('image'), 'galleries'),
            'alt_text' => $validated['alt_text'] ?? null,
            'caption' => $validated['caption'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => Gallery::max('sort_order') + 1,
        ]);

        $this->logActivity('created', $gallery, "Menambah foto galeri \"{$gallery->caption}\"");

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', "Foto galeri \"{$gallery->caption}\" berhasil ditambahkan.");
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'in:tempat,treatment,aktivitas,promo'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($gallery->image);
        }

        $gallery->update([
            'category' => $validated['category'],
            'image' => $request->hasFile('image')
                ? $this->uploadImage($request->file('image'), 'galleries')
                : $gallery->image,
            'alt_text' => $validated['alt_text'] ?? null,
            'caption' => $validated['caption'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $changes = $this->diffChanges($gallery);
        $this->logActivity('updated', $gallery, "Memperbarui galeri \"{$gallery->caption}\"", $changes);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', "Foto galeri \"{$gallery->caption}\" berhasil diperbarui.");
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        $this->deleteImage($gallery->image);
        $this->logActivity('deleted', $gallery, "Menghapus galeri \"{$gallery->caption}\"");
        $gallery->delete();

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Foto galeri berhasil dihapus.');
    }
}
