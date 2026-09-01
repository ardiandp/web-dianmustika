<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Customer;
use App\Models\Service;
use App\Models\TreatmentVisit;
use App\Traits\LogsActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    use LogsActivity;

    public function index(Request $request): View
    {
        $query = Consultation::with('customer')->withCount('treatmentVisits');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('submitted_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('submitted_at', '<=', $request->date_to);
        }

        $consultations = $query->orderByDesc('submitted_at')->get();

        return view('admin.consultations.index', compact('consultations'));
    }

    public function show(Consultation $consultation): View
    {
        $consultation->load(['customer', 'treatmentVisits.service']);
        $steps = config('consultation.steps');
        $statuses = Consultation::STATUS;
        $services = Service::active()->ordered()->get(['id', 'name']);

        return view('admin.consultations.show', compact('consultation', 'steps', 'statuses', 'services'));
    }

    public function update(Request $request, Consultation $consultation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Consultation::STATUS))],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $consultation->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        $this->logActivity('updated', $consultation, "Memperbarui status konsultasi #{$consultation->id} ke \"{$consultation->statusLabel()}\"");

        return back()->with('success', 'Status konsultasi berhasil diperbarui.');
    }

    public function destroy(Consultation $consultation): RedirectResponse
    {
        $this->logActivity('deleted', $consultation, "Menghapus konsultasi #{$consultation->id}");

        $consultation->delete();

        return redirect()
            ->route('admin.consultations.index')
            ->with('success', 'Konsultasi berhasil dihapus.');
    }
}
