<x-layouts.admin title="Edit Kunjungan">
    <x-admin.page-header title="Edit Kunjungan Treatment" description="Perbarui catatan kunjungan treatment." />

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.treatment-visits.update', $treatmentVisit) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Customer</label>
                    <input type="text" class="form-control" value="{{ $treatmentVisit->customer->name }} ({{ $treatmentVisit->customer->phone }})" disabled>
                </div>

                <div class="form-group">
                    <label>Treatment / Layanan</label>
                    <select name="service_id" class="form-control">
                        <option value="">— Pilih Layanan —</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" @selected($treatmentVisit->service_id == $service->id)>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Kunjungan</label>
                            <input type="date" name="visit_date" value="{{ $treatmentVisit->visit_date?->format('Y-m-d') }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                @foreach (\App\Models\TreatmentVisit::STATUS as $key => $label)
                                    <option value="{{ $key }}" @selected($treatmentVisit->status == $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan Therapist</label>
                    <textarea name="therapist_notes" rows="3" class="form-control">{{ $treatmentVisit->therapist_notes }}</textarea>
                </div>
                <div class="form-group">
                    <label>Catatan Setelah Treatment</label>
                    <textarea name="post_treatment_notes" rows="3" class="form-control">{{ $treatmentVisit->post_treatment_notes }}</textarea>
                </div>
                <div class="form-group">
                    <label>Rekomendasi Kunjungan Berikutnya</label>
                    <textarea name="recommendation" rows="2" class="form-control">{{ $treatmentVisit->recommendation }}</textarea>
                </div>
                <div class="form-group">
                    <label>Tanggal Follow-up</label>
                    <input type="date" name="next_follow_up_at" value="{{ $treatmentVisit->next_follow_up_at?->format('Y-m-d') }}" class="form-control">
                </div>

                <x-admin.form-actions :cancel="route('admin.customers.show', $treatmentVisit->customer_id)" />
            </form>
        </div>
    </div>
</x-layouts.admin>
