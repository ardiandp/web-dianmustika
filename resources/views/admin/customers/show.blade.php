<x-layouts.admin title="Detail Customer">
    <x-admin.page-header title="Detail Customer"
        description="Profil, riwayat konsultasi, dan kunjungan treatment customer." />

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-user mr-1"></i> Profil</h3></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nama</dt>
                        <dd class="col-sm-7">{{ $customer->name }}</dd>
                        <dt class="col-sm-5">WhatsApp</dt>
                        <dd class="col-sm-7">{{ $customer->phone }}</dd>
                        <dt class="col-sm-5">Instagram</dt>
                        <dd class="col-sm-7">{{ $customer->instagram ?: '—' }}</dd>
                        <dt class="col-sm-5">Alamat</dt>
                        <dd class="col-sm-7">{{ $customer->address ?: '—' }}</dd>
                        <dt class="col-sm-5">Tinggi</dt>
                        <dd class="col-sm-7">{{ $customer->height_cm ? $customer->height_cm.' cm' : '—' }}</dd>
                        <dt class="col-sm-5">Berat</dt>
                        <dd class="col-sm-7">{{ $customer->weight_kg ? $customer->weight_kg.' kg' : '—' }}</dd>
                        <dt class="col-sm-5">Kelahiran ke-</dt>
                        <dd class="col-sm-7">{{ $customer->birth_count ?: '—' }}</dd>
                        <dt class="col-sm-5">Follow IG</dt>
                        <dd class="col-sm-7">{{ $customer->follow_ig ? 'Ya' : 'Belum' }}</dd>
                        <dt class="col-sm-5">Bergabung</dt>
                        <dd class="col-sm-7">{{ $customer->created_at?->format('d M Y') }}</dd>
                    </dl>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-default btn-sm mt-3"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
                    <a href="{{ route('admin.treatment-visits.create', ['customer' => $customer->id]) }}" class="btn btn-success btn-sm mt-3"><i class="fas fa-plus mr-1"></i>Tambah Kunjungan</a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            {{-- Riwayat konsultasi --}}
            <div class="card card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-list-alt mr-1"></i> Riwayat Konsultasi</h3></div>
                <div class="card-body p-0">
                    @forelse ($customer->consultations as $consultation)
                        <div class="p-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong>Konsultasi #{{ $consultation->id }}</strong>
                                <span class="badge badge-info">{{ $consultation->statusLabel() }}</span>
                            </div>
                            <small class="text-muted">{{ $consultation->submitted_at?->format('d M Y H:i') }}</small>
                            <div class="mt-2">
                                <a href="{{ route('admin.consultations.show', $consultation) }}" class="btn btn-xs btn-primary">Lihat</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted p-3 mb-0">Belum ada konsultasi.</p>
                    @endforelse
                </div>
            </div>

            {{-- Riwayat kunjungan --}}
            <div class="card card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-spa mr-1"></i> Riwayat Kunjungan Treatment</h3></div>
                <div class="card-body p-0">
                    @forelse ($customer->treatmentVisits as $visit)
                        <div class="p-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $visit->service?->name ?: '—' }}</strong>
                                <span class="badge badge-secondary">{{ $visit->statusLabel() }}</span>
                            </div>
                            <small class="text-muted">{{ $visit->visit_date?->format('d M Y') ?: '—' }}</small>
                            @if ($visit->next_follow_up_at)
                                <br><small class="text-info">Follow-up: {{ $visit->next_follow_up_at->format('d M Y') }}</small>
                            @endif
                            <div class="mt-2">
                                <a href="{{ route('admin.treatment-visits.edit', $visit) }}" class="btn btn-xs btn-info">Edit</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted p-3 mb-0">Belum ada kunjungan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
