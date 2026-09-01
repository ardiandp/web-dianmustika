<x-layouts.admin title="Detail Konsultasi">
    <x-admin.page-header title="Detail Konsultasi #{{ $consultation->id }}"
        description="Lihat jawaban customer, ubah status, dan kelola kunjungan." />

    <div class="row">
        <div class="col-lg-8">
            {{-- Status update --}}
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-exchange-alt mr-1"></i> Status Konsultasi</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.consultations.update', $consultation) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group mb-0">
                                    <select name="status" class="form-control">
                                        @foreach ($statuses as $key => $label)
                                            <option value="{{ $key }}" @selected($consultation->status == $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <button class="btn btn-primary"><i class="fas fa-save mr-1"></i>Update Status</button>
                                <a href="{{ route('admin.consultations.index') }}" class="btn btn-default">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Jawaban customer --}}
            <div class="card card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-list-alt mr-1"></i> Jawaban Customer</h3></div>
                <div class="card-body">
                    @php $answers = $consultation->answers ?? []; @endphp
                    @forelse ($steps as $step)
                        <h5 class="mt-3 mb-2 border-bottom pb-1 text-primary">{{ $step['title'] }}</h5>
                        @php
                            $hasAnswer = false;
                        @endphp
                        @foreach ($step['fields'] as $field)
                            @php
                                $key = $field['key'];
                                $has = isset($answers[$key]) && !empty($answers[$key]);
                                $hasArr = is_array($answers[$key] ?? null) && count(array_filter($answers[$key])) > 0;
                                $display = $has || $hasArr;
                                // skip mirror keys (checkbox "lainnya" textarea)
                                $isOthersValue = in_array($key, ['treatment_goals_other', 'health_conditions_other']);
                                if ($isOthersValue && !isset($answers[$key])) { $display = false; }
                            @endphp
                            @if ($display)
                                @php $hasAnswer = true; @endphp
                                <div class="row mb-1">
                                    <div class="col-sm-5 text-muted">{{ $consultation->answerLabel($key) ?? $field['label'] }}</div>
                                    <div class="col-sm-7">
                                        @if (is_array($answers[$key]))
                                            {{ implode(', ', array_map(fn($v) => $consultation->answerValue($key, $v), $answers[$key])) }}
                                        @else
                                            {{ $consultation->answerValue($key, $answers[$key]) }}
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @php unset($answers[$key]); @endphp
                        @endforeach
                        @if (!$hasAnswer)
                            <p class="text-muted small">Tidak ada jawaban diisi.</p>
                        @endif
                    @empty
                        <p class="text-muted">Belum ada jawaban.</p>
                    @endforelse

                    @if (!empty($answers))
                        <h5 class="mt-4 mb-2 border-bottom pb-1 text-primary">Lainnya</h5>
                        @foreach ($answers as $key => $value)
                            @if (!empty($value))
                                <div class="row mb-1">
                                    <div class="col-sm-5 text-muted">{{ $consultation->answerLabel($key) ?? $key }}</div>
                                    <div class="col-sm-7">{{ is_array($value) ? implode(', ', $value) : $value }}</div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Customer info --}}
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-user mr-1"></i> Customer</h3></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Nama</dt>
                        <dd class="col-sm-7">{{ $consultation->customer->name }}</dd>
                        <dt class="col-sm-5">WhatsApp</dt>
                        <dd class="col-sm-7">{{ $consultation->customer->phone }}</dd>
                        @if ($consultation->customer->instagram)
                            <dt class="col-sm-5">Instagram</dt>
                            <dd class="col-sm-7">{{ $consultation->customer->instagram }}</dd>
                        @endif
                        <dt class="col-sm-5">Alamat</dt>
                        <dd class="col-sm-7">{{ $consultation->customer->address ?: '—' }}</dd>
                    </dl>
                    <a href="{{ route('admin.customers.show', $consultation->customer_id) }}" class="btn btn-info btn-sm mt-2"><i class="fas fa-user mr-1"></i>Lihat Profil Customer</a>
                </div>
            </div>

            {{-- Catatan Admin --}}
            <div class="card card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-edit mr-1"></i> Catatan Admin</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.consultations.update', $consultation) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="{{ $consultation->status }}">
                        <textarea name="admin_notes" rows="4" class="form-control mb-2" placeholder="Catatan internal admin...">{{ $consultation->admin_notes }}</textarea>
                        <button class="btn btn-sm btn-primary"><i class="fas fa-save mr-1"></i>Simpan Catatan</button>
                    </form>
                </div>
            </div>

            {{-- Kunjungan treatment --}}
            <div class="card card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-spa mr-1"></i> Kunjungan Treatment</h3>
                    <a href="{{ route('admin.treatment-visits.create', ['customer' => $consultation->customer_id, 'consultation' => $consultation->id]) }}" class="btn btn-sm btn-success float-right"><i class="fas fa-plus mr-1"></i>Tambah</a>
                </div>
                <div class="card-body p-0">
                    @if ($consultation->treatmentVisits->isEmpty())
                        <p class="text-muted p-3 mb-0">Belum ada kunjungan.</p>
                    @else
                        <table class="table table-sm table-hover mb-0">
                            <tbody>
                                @foreach ($consultation->treatmentVisits as $visit)
                                    <tr>
                                        <td>
                                            {{ $visit->service?->name ?: '—' }}
                                            <br><small class="text-muted">{{ $visit->visit_date?->format('d M Y') }} · {{ $visit->statusLabel() }}</small>
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.treatment-visits.edit', $visit) }}" class="btn btn-xs btn-info"><i class="fas fa-edit"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
