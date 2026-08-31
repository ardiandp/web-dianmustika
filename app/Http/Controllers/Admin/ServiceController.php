<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Faq;
use App\Models\Location;
use App\Models\PageView;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceGallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->with(['category', 'locations', 'seo'])
            ->ordered()
            ->get();

        $serviceViews = PageView::query()
            ->select('path', DB::raw('COUNT(*) as views'))
            ->where('path', 'like', '/layanan/%')
            ->groupBy('path')
            ->pluck('views', 'path');

        return view('admin.services.index', compact('services', 'serviceViews'));
    }

    public function create(): View
    {
        $categories = ServiceCategory::active()->ordered()->get();
        $locations = Location::active()->ordered()->get();
        $allServices = Service::ordered()->get(['id', 'name', 'slug']);
        $articles = Article::active()->ordered()->get(['id', 'title', 'slug']);

        return view('admin.services.create', compact('categories', 'locations', 'allServices', 'articles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateService($request);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request->file('image'), 'services');
        } elseif ($request->filled('image_library')) {
            $imagePath = $request->input('image_library');
        }

        $service = Service::create([
            'service_category_id' => $validated['service_category_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(Service::class, $validated['name']),
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'benefits' => $request->benefits ? $this->parseLines($request->benefits) : null,
            'cocok_untuk' => $validated['cocok_untuk'] ?? null,
            'perhatian' => $validated['perhatian'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'price' => $validated['price'] ?? null,
            'harga_label' => $validated['harga_label'] ?? null,
            'tipe_harga' => $validated['tipe_harga'] ?? 'tetap',
            'cta_text' => $validated['cta_text'] ?? 'Reservasi Sekarang',
            'cta_url' => $validated['cta_url'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'focus_keyword' => $validated['focus_keyword'] ?? null,
            'secondary_keywords' => $validated['secondary_keywords'] ?? null,
            'note' => $validated['note'] ?? null,
            'image' => $imagePath,
            'alt_text' => $validated['alt_text'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => Service::max('sort_order') + 1,
        ]);

        $this->syncRelations($request, $service);
        $this->syncGalleries($request, $service);
        $this->syncFaqs($request, $service);
        $this->syncSeo($service, $request->all());

        return redirect()
            ->route('admin.services.index')
            ->with('success', "Layanan \"{$service->name}\" berhasil dibuat.");
    }

    public function edit(Service $service): View
    {
        $service->load(['locations', 'relatedServices', 'articles', 'galleries', 'faqs', 'seo']);
        $categories = ServiceCategory::active()->ordered()->get();
        $locations = Location::active()->ordered()->get();
        $allServices = Service::where('id', '!=', $service->id)->ordered()->get(['id', 'name', 'slug']);
        $articles = Article::active()->ordered()->get(['id', 'title', 'slug']);

        return view('admin.services.edit', compact('service', 'categories', 'locations', 'allServices', 'articles'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $this->validateService($request, $service->id);

        $imagePath = $this->resolveImage($request, 'image', $service->image, 'services');

        $service->update([
            'service_category_id' => $validated['service_category_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $request->slug ?: $this->makeSlug(Service::class, $validated['name'], $service->id),
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'benefits' => $request->benefits ? $this->parseLines($request->benefits) : null,
            'cocok_untuk' => $validated['cocok_untuk'] ?? null,
            'perhatian' => $validated['perhatian'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'price' => $validated['price'] ?? null,
            'harga_label' => $validated['harga_label'] ?? null,
            'tipe_harga' => $validated['tipe_harga'] ?? 'tetap',
            'cta_text' => $validated['cta_text'] ?? 'Reservasi Sekarang',
            'cta_url' => $validated['cta_url'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'focus_keyword' => $validated['focus_keyword'] ?? null,
            'secondary_keywords' => $validated['secondary_keywords'] ?? null,
            'note' => $validated['note'] ?? null,
            'image' => $imagePath,
            'alt_text' => $validated['alt_text'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncRelations($request, $service);
        $this->syncGalleries($request, $service);
        $this->syncFaqs($request, $service);
        $this->syncSeo($service, $request->all());

        return redirect()
            ->route('admin.services.index')
            ->with('success', "Layanan \"{$service->name}\" berhasil diperbarui.");
    }

    public function destroy(Service $service): RedirectResponse
    {
        // Delete gallery images
        foreach ($service->galleries as $gallery) {
            $this->deleteImage($gallery->image);
        }

        $this->deleteImage($service->image);
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }

    private function validateService(Request $request, ?int $excludeId = null): array
    {
        return $request->validate([
            'service_category_id' => ['nullable', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', 'unique:services,slug'.($excludeId ? ','.$excludeId : '')],
            'short_description' => ['required', 'string'],
            'description' => ['required', 'string'],
            'benefits' => ['nullable', 'string'],
            'cocok_untuk' => ['nullable', 'string'],
            'perhatian' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:255'],
            'tipe_harga' => ['nullable', 'in:tetap,mulai_dari,per_lokasi,hubungi_kami'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'harga_label' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'image_library' => ['nullable', 'string', 'max:500'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:100'],
            'cta_url' => ['nullable', 'url', 'max:500'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'focus_keyword' => ['nullable', 'string', 'max:255'],
            'secondary_keywords' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'location_ids' => ['nullable', 'array'],
            'location_ids.*' => ['exists:locations,id'],
            'related_service_ids' => ['nullable', 'array'],
            'related_service_ids.*' => ['exists:services,id'],
            'article_ids' => ['nullable', 'array'],
            'article_ids.*' => ['exists:articles,id'],
            // Gallery images (multiple)
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpeg,png,webp', 'max:2048'],
            'gallery_alt_texts' => ['nullable', 'array'],
            'gallery_alt_texts.*' => ['nullable', 'string', 'max:255'],
            'gallery_captions' => ['nullable', 'array'],
            'gallery_captions.*' => ['nullable', 'string', 'max:255'],
            // Existing gallery management
            'existing_gallery_ids' => ['nullable', 'array'],
            'existing_gallery_ids.*' => ['exists:service_galleries,id'],
            'remove_gallery_ids' => ['nullable', 'array'],
            'remove_gallery_ids.*' => ['exists:service_galleries,id'],
            // FAQ repeater
            'faqs' => ['nullable', 'array'],
            'faqs.*.id' => ['nullable', 'exists:faqs,id'],
            'faqs.*.question' => ['required_with:faqs', 'string', 'max:500'],
            'faqs.*.answer' => ['required_with:faqs', 'string'],
            'faqs.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'faqs.*.is_active' => ['nullable', 'boolean'],
            'remove_faq_ids' => ['nullable', 'array'],
            'remove_faq_ids.*' => ['exists:faqs,id'],
            // SEO
            'seo_title' => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:170'],
            'seo_keywords' => ['nullable', 'string'],
            'seo_canonical' => ['nullable', 'url', 'max:500'],
        ]);
    }

    private function syncRelations(Request $request, Service $service): void
    {
        // Locations (reuse existing location_service pivot)
        $service->locations()->sync($request->input('location_ids', []));

        // Related services (exclude self)
        $relatedIds = collect($request->input('related_service_ids', []))
            ->filter(fn ($id) => (int) $id !== $service->id)
            ->values()
            ->all();

        $syncData = [];
        foreach ($relatedIds as $index => $id) {
            $syncData[$id] = ['sort_order' => $index];
        }
        $service->relatedServices()->sync($syncData);

        // Articles
        $service->articles()->sync($request->input('article_ids', []));
    }

    private function syncGalleries(Request $request, Service $service): void
    {
        // Remove galleries marked for deletion
        $removeIds = $request->input('remove_gallery_ids', []);
        if (! empty($removeIds)) {
            $galleries = ServiceGallery::whereIn('id', $removeIds)->where('service_id', $service->id)->get();
            foreach ($galleries as $gallery) {
                $this->deleteImage($gallery->image);
                $gallery->delete();
            }
        }

        // Add new gallery images
        if ($request->hasFile('gallery_images')) {
            $maxOrder = $service->galleries()->max('sort_order') ?? 0;
            $altTexts = $request->input('gallery_alt_texts', []);
            $captions = $request->input('gallery_captions', []);

            foreach ($request->file('gallery_images') as $index => $file) {
                if (! $file) {
                    continue;
                }

                $service->galleries()->create([
                    'image' => $this->uploadImage($file, 'services/gallery'),
                    'alt_text' => $altTexts[$index] ?? null,
                    'caption' => $captions[$index] ?? null,
                    'sort_order' => ++$maxOrder,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function syncFaqs(Request $request, Service $service): void
    {
        // Remove FAQs marked for deletion
        $removeIds = $request->input('remove_faq_ids', []);
        if (! empty($removeIds)) {
            Faq::whereIn('id', $removeIds)->where('service_id', $service->id)->delete();
        }

        $faqs = $request->input('faqs', []);
        if (empty($faqs)) {
            return;
        }

        foreach ($faqs as $index => $faqData) {
            if (empty($faqData['question']) || empty($faqData['answer'])) {
                continue;
            }

            $data = [
                'question' => $faqData['question'],
                'answer' => $faqData['answer'],
                'sort_order' => $faqData['sort_order'] ?? $index,
                'is_active' => isset($faqData['is_active']) ? (bool) $faqData['is_active'] : true,
                'category' => 'umum',
                'service_id' => $service->id,
            ];

            if (! empty($faqData['id'])) {
                $faq = Faq::where('id', $faqData['id'])->where('service_id', $service->id)->first();
                if ($faq) {
                    $faq->update($data);
                }
            } else {
                Faq::create($data);
            }
        }
    }
}
