<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Traits\LogsActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    use LogsActivity;
    public function index(): View
    {
        $testimonials = Testimonial::query()
            ->orderBy('sort_order')
            ->get();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'treatment' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $testimonial = Testimonial::create([
            'customer_name' => $validated['customer_name'],
            'treatment' => $validated['treatment'] ?? null,
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'image' => $request->hasFile('image')
                ? $this->uploadImage($request->file('image'), 'testimonials')
                : null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => Testimonial::max('sort_order') + 1,
        ]);

        $this->logActivity('created', $testimonial, "Membuat testimonial \"{$testimonial->customer_name}\"");

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', "Testimonial dari \"{$testimonial->customer_name}\" berhasil ditambahkan.");
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'treatment' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($testimonial->image);
        }

        $testimonial->update([
            'customer_name' => $validated['customer_name'],
            'treatment' => $validated['treatment'] ?? null,
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'image' => $request->hasFile('image')
                ? $this->uploadImage($request->file('image'), 'testimonials')
                : $testimonial->image,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        $changes = $this->diffChanges($testimonial);
        $this->logActivity('updated', $testimonial, "Memperbarui testimonial \"{$testimonial->customer_name}\"", $changes);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', "Testimonial dari \"{$testimonial->customer_name}\" berhasil diperbarui.");
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->deleteImage($testimonial->image);
        $this->logActivity('deleted', $testimonial, "Menghapus testimonial \"{$testimonial->customer_name}\"");
        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial berhasil dihapus.');
    }
}
