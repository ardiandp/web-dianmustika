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

class CustomerController extends Controller
{
    use LogsActivity;

    public function index(Request $request): View
    {
        $query = Customer::withCount(['consultations', 'treatmentVisits']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $customers = $query->orderByDesc('created_at')->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer): View
    {
        $customer->load(['consultations', 'treatmentVisits.service']);

        return view('admin.customers.show', compact('customer'));
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $this->logActivity('deleted', $customer, "Menghapus customer \"{$customer->name}\"");

        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}
