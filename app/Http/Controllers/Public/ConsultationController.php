<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Customer;
use App\Models\Service;
use App\Services\SeoService;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    /**
     * Landing page untuk konsultasi.
     */
    public function landing(): View
    {
        $flow = config('consultation');

        $seo = SeoService::forPage([
            'title' => 'Konsultasi Homecare Pascamelahirkan | Dian Mustika',
            'description' => 'Isi form konsultasi Dian Mustika untuk membantu menentukan kebutuhan perawatan homecare pascamelahirkan, massage, breastcare, dan perawatan ibu & bayi.',
            'robots' => 'index, follow',
        ]);

        return view('pages.consultation.landing', compact('flow', 'seo'));
    }

    /**
     * Form konsultasi multi-step.
     */
    public function create(): View
    {
        $flow = config('consultation');
        $steps = $flow['steps'];

        // Pertanyaan dicari dari config; daftar layanan (services) dipakai untuk referensi treatment.
        $services = Service::active()->ordered()->get(['id', 'name', 'slug', 'short_description']);

        $seo = SeoService::forPage([
            'title' => 'Form Konsultasi Homecare Pascamelahirkan | Dian Mustika',
            'description' => 'Isi form konsultasi homecare pascamelahirkan Dian Mustika untuk membantu menentukan kebutuhan perawatan ibu & bayi Anda.',
            'robots' => 'index, follow',
        ]);

        return view('pages.consultation.form', compact('flow', 'steps', 'services', 'seo'));
    }

    /**
     * Simpan hasil konsultasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $flow = config('consultation');
        $steps = $flow['steps'];

        // Keseluruhan jawaban dikirim sebagai JSON via field data_json.
        $payload = $request->input('data_json');
        $data = json_decode($payload, true);

        if (! is_array($data) || $data === []) {
            return back()->withErrors(['form' => 'Data konsultasi kosong. Silakan isi ulang.']);
        }

        // Validasi field wajib + format.
        $errors = [];
        $requiredKeys = [];
        foreach ($steps as $step) {
            foreach ($step['fields'] as $field) {
                if (! empty($field['required'])) {
                    $requiredKeys[] = $field['key'];
                }
            }
        }

        $phone = Customer::normalizePhone($data['phone'] ?? null);
        if (! $phone) {
            $errors['phone'] = 'Nomor WhatsApp tidak valid.';
        }

        foreach ($requiredKeys as $key) {
            $val = $data[$key] ?? null;
            if (is_array($val)) {
                $val = implode('', $val);
            }
            if (trim((string) $val) === '') {
                $errors[$key] = 'Mohon isi jawaban ini.';
            }
        }

        if (! empty($errors)) {
            return back()->withErrors($errors);
        }

        $data['phone'] = $phone;

        // Buat / cari customer berdasarkan nomor WhatsApp (Business Rule 1).
        $customer = Customer::firstOrCreate(
            ['phone' => $phone],
            [
                'name' => $data['name'] ?? 'Customer',
                'instagram' => ! empty($data['no_instagram']) || empty($data['instagram']) ? null : $data['instagram'],
                'address' => $data['address'] ?? null,
                'height_cm' => isset($data['height_cm']) ? (int) $data['height_cm'] : null,
                'weight_kg' => isset($data['weight_kg']) ? (int) $data['weight_kg'] : null,
                'birth_count' => $data['birth_count'] ?? null,
                'follow_ig' => ($data['follow_ig'] ?? '') === 'sudah',
            ]
        );

        // Jika customer sudah ada, perbarui beberapa data profil bila kosong baru diisi.
        if ($customer->wasRecentlyCreated === false) {
            $customer->update([
                'instagram' => ! empty($data['no_instagram']) ? null : ($data['instagram'] ?? $customer->instagram),
                'follow_ig' => ($data['follow_ig'] ?? '') === 'sudah' ? true : $customer->follow_ig,
            ]);
        }

        $consultation = Consultation::create([
            'customer_id' => $customer->id,
            'flow_name' => $flow['flow_name'],
            'status' => 'baru',
            'answers' => $data,
            'submitted_at' => now(),
            'consent_at' => now(),
        ]);

        session()->forget('consultation_draft');
        session()->flash('last_consultation_id', $consultation->id);

        return redirect()->route('consultation.success');
    }

    /**
     * Halaman sukses setelah submit.
     */
    public function success(): View
    {
        $consultationId = session('last_consultation_id');
        $consultation = $consultationId ? Consultation::with('customer')->find($consultationId) : null;

        $customerName = $consultation?->customer?->name ?: 'Anda';

        $waMessage = "Halo Dian Mustika,\n\n"
            ."Saya sudah mengisi Form Konsultasi Homecare Pascamelahirkan.\n\n"
            ."Nama: {$customerName}\n"
            .'Tanggal pengisian: '.now()->format('d/m/Y')."\n\n"
            .'Mohon dibantu untuk konfirmasi konsultasi dan treatment.'
            ."\n\nTerima kasih.";

        $waUrl = WhatsAppService::url($waMessage);

        $seo = SeoService::forPage([
            'title' => 'Konsultasi Terkirim | Dian Mustika',
            'description' => 'Terima kasih, konsultasi Anda telah diterima oleh tim Dian Mustika.',
            'robots' => 'noindex, nofollow',
        ]);

        return view('pages.consultation.success', compact('waUrl', 'consultation', 'seo'));
    }
}
