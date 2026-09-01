<x-layouts.admin title="Tambah Kunjungan">
    <x-admin.page-header title="Tambah Kunjungan Treatment" description="Catat kunjungan treatment untuk customer." />

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.treatment-visits.store') }}">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                @if ($consultation)
                    <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">
                @endif

                <div class="form-group">
                    <label>Customer</label>
                    <input type="text" class="form-control" value="{{ $customer->name }} ({{ $customer->phone }})" disabled>
                </div>

                <div class="form-group">
                    <label>Treatment / Layanan</label>
                    <select name="service_id" class="form-control">
                        <option value="">— Pilih Layanan —</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('service_id')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Kunjungan</label>
                            <input type="date" name="visit_date" value="{{ old('visit_date') }}" class="form-control">
                            @error('visit_date')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                @foreach (\App\Models\TreatmentVisit::STATUS as $key => $label)
                                    <option value="{{ $key }}" @selected(old('status', 'dijadwalkan') == $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan Therapist</label>
                    <textarea name="therapist_notes" rows="3" class="form-control">{{ old('therapist_notes') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Catatan Setelah Treatment</label>
                    <textarea name="post_treatment_notes" rows="3" class="form-control">{{ old('post_treatment_notes') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Rekomendasi Kunjungan Berikutnya</label>
                    <textarea name="recommendation" rows="2" class="form-control">{{ old('recommendation') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Tanggal Follow-up</label>
                    <input type="date" name="next_follow_up_at" value="{{ old('next_follow_up_at') }}" class="form-control">
                </div>

                <x-admin.form-actions :cancel="route('admin.customers.show', $customer->id)" />
            </form>
        </div>
    </div>
</x-layouts.admin>
