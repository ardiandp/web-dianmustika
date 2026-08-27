<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Location;
use App\Models\Service;
use App\Traits\LogsActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    use LogsActivity;
    public function index(): View
    {
        $faqs = Faq::query()
            ->with(['service', 'location'])
            ->orderBy('sort_order')
            ->get();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        $services = Service::active()->ordered()->get();
        $locations = Location::active()->ordered()->get();

        return view('admin.faqs.create', compact('services', 'locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'in:umum,layanan,harga,lokasi,perawatan'],
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'service_id' => ['nullable', 'exists:services,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $faq = Faq::create([
            'category' => $validated['category'],
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'service_id' => $validated['service_id'] ?? null,
            'location_id' => $validated['location_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => Faq::max('sort_order') + 1,
        ]);

        $this->logActivity('created', $faq, "Membuat FAQ \"{$faq->question}\"");

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', "FAQ \"{$faq->question}\" berhasil dibuat.");
    }

    public function edit(Faq $faq): View
    {
        $services = Service::active()->ordered()->get();
        $locations = Location::active()->ordered()->get();

        return view('admin.faqs.edit', compact('faq', 'services', 'locations'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'in:umum,layanan,harga,lokasi,perawatan'],
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'service_id' => ['nullable', 'exists:services,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $faq->update([
            'category' => $validated['category'],
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'service_id' => $validated['service_id'] ?? null,
            'location_id' => $validated['location_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $changes = $this->diffChanges($faq);
        $this->logActivity('updated', $faq, "Memperbarui FAQ \"{$faq->question}\"", $changes);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', "FAQ \"{$faq->question}\" berhasil diperbarui.");
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $this->logActivity('deleted', $faq, "Menghapus FAQ \"{$faq->question}\"");
        $faq->delete();

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil dihapus.');
    }
}
