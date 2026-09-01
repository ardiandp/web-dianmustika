<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Service;
use App\Models\TreatmentVisit;
use App\Traits\LogsActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TreatmentVisitController extends Controller
{
    use LogsActivity;

    public function create(Request $request, Customer $customer): View
    {
        $services = Service::active()->ordered()->get(['id', 'name']);
        $consultation = null;

        $consultationId = $request->integer('consultation');
        if ($consultationId > 0) {
            $consultation = $customer->consultations()->find($consultationId);
        }

        return view('admin.treatment-visits.create', compact('customer', 'consultation', 'services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'consultation_id' => ['nullable', 'exists:consultations,id'],
            'service_id' => ['nullable', 'exists:services,id'],
            'visit_date' => ['nullable', 'date'],
            'status' => ['required', 'in:'.implode(',', array_keys(TreatmentVisit::STATUS))],
            'therapist_notes' => ['nullable', 'string'],
            'post_treatment_notes' => ['nullable', 'string'],
            'recommendation' => ['nullable', 'string'],
            'next_follow_up_at' => ['nullable', 'date'],
        ]);

        $visit = TreatmentVisit::create([
            'customer_id' => $validated['customer_id'],
            'consultation_id' => $validated['consultation_id'] ?? null,
            'service_id' => $validated['service_id'] ?? null,
            'visit_date' => $validated['visit_date'] ?? null,
            'status' => $validated['status'],
            'therapist_notes' => $validated['therapist_notes'] ?? null,
            'post_treatment_notes' => $validated['post_treatment_notes'] ?? null,
            'recommendation' => $validated['recommendation'] ?? null,
            'next_follow_up_at' => $validated['next_follow_up_at'] ?? null,
        ]);

        $this->logActivity('created', $visit, "Membuat kunjungan treatment untuk customer #{$visit->customer_id}");

        return redirect()
            ->route('admin.customers.show', $validated['customer_id'])
            ->with('success', 'Kunjungan treatment berhasil ditambahkan.');
    }

    public function edit(TreatmentVisit $treatmentVisit): View
    {
        $treatmentVisit->load('customer');
        $services = Service::active()->ordered()->get(['id', 'name']);

        return view('admin.treatment-visits.edit', compact('treatmentVisit', 'services'));
    }

    public function update(Request $request, TreatmentVisit $treatmentVisit): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['nullable', 'exists:services,id'],
            'visit_date' => ['nullable', 'date'],
            'status' => ['required', 'in:'.implode(',', array_keys(TreatmentVisit::STATUS))],
            'therapist_notes' => ['nullable', 'string'],
            'post_treatment_notes' => ['nullable', 'string'],
            'recommendation' => ['nullable', 'string'],
            'next_follow_up_at' => ['nullable', 'date'],
        ]);

        $treatmentVisit->update($validated);

        $this->logActivity('updated', $treatmentVisit, "Memperbarui kunjungan treatment #{$treatmentVisit->id}");

        return redirect()
            ->route('admin.customers.show', $treatmentVisit->customer_id)
            ->with('success', 'Kunjungan treatment berhasil diperbarui.');
    }

    public function destroy(TreatmentVisit $treatmentVisit): RedirectResponse
    {
        $customerId = $treatmentVisit->customer_id;
        $this->logActivity('deleted', $treatmentVisit, "Menghapus kunjungan treatment #{$treatmentVisit->id}");

        $treatmentVisit->delete();

        return redirect()
            ->route('admin.customers.show', $customerId)
            ->with('success', 'Kunjungan treatment berhasil dihapus.');
    }
}
