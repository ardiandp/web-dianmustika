<x-layouts.app :seo="$seo" active="">

    @php
        $stepsJson = json_encode(array_map(function ($step) {
            return ['key' => $step['key'], 'title' => $step['title'], 'fields' => $step['fields']];
        }, $steps), JSON_UNESCAPED_UNICODE);
    @endphp

    <section class="relative overflow-hidden bg-brand-950">
        <div class="relative mx-auto max-w-3xl px-4 py-12 text-center sm:px-6">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-gold-400">Dian Mustika</p>
            <h1 class="mt-3 font-display text-3xl font-semibold text-cream sm:text-4xl">Konsultasi Homecare</h1>
            <p class="mt-3 text-sm text-brand-100/70">Isi form secara bertahap. Jawaban Anda akan tersimpan otomatis di perangkat ini.</p>
        </div>
    </section>

    <section class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-ink/5 sm:p-8">

            {{-- Progress bar --}}
            <div class="mb-8">
                <div class="flex items-center justify-between text-xs text-ink/50">
                    <span id="step-label" class="font-semibold text-brand-800">Langkah 1 dari {{ count($steps) }}</span>
                    <span id="step-percent">0%</span>
                </div>
                <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-brand-100">
                    <div id="progress-bar" class="h-full rounded-full bg-gold-500 transition-all duration-300" style="width:0%"></div>
                </div>
                <div id="step-dots" class="mt-3 flex flex-wrap gap-1.5">
                    @foreach ($steps as $i => $step)
                        <span data-dot="{{ $i }}" class="h-2 flex-1 rounded-full bg-brand-100 transition {{ $i === 0 ? 'bg-brand-400' : '' }}"></span>
                    @endforeach
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="font-semibold">Mohon periksa kembali isian Anda:</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="consultation-form" method="POST" action="{{ route('consultation.store') }}" novalidate>
                @csrf
                <input type="hidden" name="data_json" id="data-json" value="">
                <input type="hidden" name="consented" id="consented" value="0">

                @foreach ($steps as $i => $step)
                    <fieldset class="step" data-step="{{ $i }}" data-key="{{ $step['key'] }}" @if ($i !== 0) hidden @endif>
                        <h2 class="font-display text-2xl font-semibold text-brand-800">{{ $step['title'] }}</h2>
                        <p class="mt-1 text-sm text-ink/50">Langkah {{ $i + 1 }} dari {{ count($steps) }}</p>
                        <div class="mt-6 space-y-6">
                            @foreach ($step['fields'] as $field)
                                <div
                                    class="question"
                                    data-question="{{ $field['key'] }}"
                                    @if (!empty($field['condition']))
                                        data-condition='{!! json_encode($field['condition'], JSON_UNESCAPED_UNICODE) !!}'
                                        data-conditional
                                    @endif
                                    @if (!empty($field['others_textarea']))
                                        data-others-textarea='{!! json_encode($field['others_textarea'], JSON_UNESCAPED_UNICODE) !!}'
                                    @endif
                                    @if (!empty($field['warning']))
                                        data-warning="1"
                                    @endif
                                >
                                    @php $fieldId = 'f_'.$field['key']; @endphp

                                    <label for="{{ $fieldId }}" class="mb-1.5 block text-sm font-semibold text-brand-800">
                                        {{ $field['label'] }}
                                        @if (!empty($field['required']))
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    @if (!empty($field['description']) && !empty($field['type']) && $field['type'] === 'radio' && empty($field['options'][0]['description']))
                                        <p class="mb-2 text-xs text-ink/50">{{ $field['description'] }}</p>
                                    @endif

                                    @if (($field['type'] ?? '') === 'textarea')
                                        <textarea
                                            id="{{ $fieldId }}"
                                            name="{{ $field['key'] }}"
                                            rows="3"
                                            placeholder="{{ $field['placeholder'] ?? '' }}"
                                            class="q-input w-full rounded-2xl border border-brand-200 bg-white px-4 py-3 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100"
                                        ></textarea>
                                    @elseif (($field['type'] ?? '') === 'checkbox')
                                        <div class="space-y-2">
                                            @foreach ($field['options'] as $opt)
                                                <label class="flex items-start gap-3 rounded-xl border border-brand-100 p-3 transition hover:border-brand-300 hover:bg-brand-50/50">
                                                    <input type="checkbox" name="{{ $field['key'] }}[]" value="{{ $opt['value'] }}" class="q-checkbox mt-0.5 h-4 w-4 rounded border-brand-300 text-brand-700 focus:ring-brand-400">
                                                    <span class="text-sm text-ink/80">{{ $opt['label'] }}</span>
                                                </label>
                                            @endforeach
                                            <div class="others-box hidden mt-2">
                                                <textarea name="{{ $field['others_textarea']['key'] ?? $field['key'].'_other' }}" rows="2" placeholder="Tuliskan lainnya..." class="q-input w-full rounded-2xl border border-brand-200 bg-white px-4 py-3 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100"></textarea>
                                            </div>
                                        </div>
                                    @elseif (($field['type'] ?? '') === 'checkbox_single')
                                        <label class="flex items-center gap-3">
                                            <input type="checkbox" name="{{ $field['key'] }}" value="1" class="q-checkbox h-4 w-4 rounded border-brand-300 text-brand-700 focus:ring-brand-400">
                                            <span class="text-sm text-ink/80">{{ $field['label'] }}</span>
                                        </label>
                                    @elseif (($field['type'] ?? '') === 'select')
                                        <select
                                            id="{{ $fieldId }}"
                                            name="{{ $field['key'] }}"
                                            class="q-input w-full rounded-2xl border border-brand-200 bg-white px-4 py-3 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100"
                                        >
                                            <option value="">— Pilih —</option>
                                            @foreach ($field['options'] as $opt)
                                                <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                                            @endforeach
                                        </select>
                                    @elseif (($field['type'] ?? '') === 'radio')
                                        <div class="space-y-2">
                                            @foreach ($field['options'] as $opt)
                                                <label class="flex items-start gap-3 rounded-xl border border-brand-100 p-3 transition hover:border-brand-300 hover:bg-brand-50/50 cursor-pointer">
                                                    <input type="radio" name="{{ $field['key'] }}" value="{{ $opt['value'] }}" class="q-radio mt-1 h-4 w-4 border-brand-300 text-brand-700 focus:ring-brand-400">
                                                    <span>
                                                        <span class="block text-sm font-medium text-ink/90">{{ $opt['label'] }}</span>
                                                        @if (!empty($opt['description']))
                                                            <span class="block text-xs text-ink/50">{{ $opt['description'] }}</span>
                                                        @endif
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="relative">
                                            <input
                                                id="{{ $fieldId }}"
                                                type="{{ ($field['type'] ?? 'text') === 'tel' ? 'tel' : (($field['type'] ?? 'text') === 'number' ? 'number' : 'text') }}"
                                                name="{{ $field['key'] }}"
                                                min="{{ ($field['type'] ?? '') === 'number' ? '0' : '' }}"
                                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                                inputmode="{{ ($field['type'] ?? '') === 'number' ? 'numeric' : (($field['type'] ?? '') === 'tel' ? 'tel' : '') }}"
                                                class="q-input w-full rounded-2xl border border-brand-200 bg-white px-4 py-3 pr-20 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100"
                                            >
                                            @if (!empty($field['unit']))
                                                <span class="absolute inset-y-0 right-4 flex items-center text-xs text-ink/40">{{ $field['unit'] }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <p class="field-error mt-1 hidden text-xs text-red-600">Mohon isi jawaban ini.</p>
                                    @if (!empty($field['warning']))
                                        <div class="warning-box mt-3 hidden rounded-2xl border border-amber-200 bg-amber-50 p-3 text-xs leading-relaxed text-amber-800">
                                            <strong>Perhatian:</strong> Informasi ini perlu dikonfirmasi terlebih dahulu oleh therapist. Jika terdapat keluhan berat atau kondisi yang mengkhawatirkan, disarankan mendapatkan pemeriksaan dari tenaga kesehatan.
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </fieldset>
                @endforeach

                {{-- Review + Consent --}}
                <fieldset class="step" data-step="{{ count($steps) }}" data-review hidden>
                    <h2 class="font-display text-2xl font-semibold text-brand-800">Review &amp; Persetujuan</h2>
                    <p class="mt-1 text-sm text-ink/50">Periksa kembali jawaban Anda sebelum mengirim. Gunakan tombol Edit untuk mengubah.</p>
                    <div id="review-body" class="mt-6 space-y-4"></div>

                    <div class="mt-8 space-y-3 rounded-2xl bg-brand-50/60 p-4">
                        <label class="flex items-start gap-3">
                            <input type="checkbox" id="consent-truth" class="mt-1 h-4 w-4 rounded border-brand-300 text-brand-700 focus:ring-brand-400">
                            <span class="text-sm text-ink/80">Saya menyatakan bahwa informasi yang saya berikan adalah benar dan dapat digunakan oleh Dian Mustika untuk membantu proses konsultasi dan persiapan treatment.</span>
                        </label>
                        <label class="flex items-start gap-3">
                            <input type="checkbox" id="consent-medical" class="mt-1 h-4 w-4 rounded border-brand-300 text-brand-700 focus:ring-brand-400">
                            <span class="text-sm text-ink/80">Saya memahami bahwa pengisian form ini bukan merupakan diagnosis medis.</span>
                        </label>
                        <p id="consent-error" class="hidden text-xs text-red-600">Harap centang semua pernyataan sebelum mengirim.</p>
                    </div>
                </fieldset>
            </form>

            {{-- Navigation --}}
            <div class="mt-8 flex items-center justify-between gap-3 border-t border-brand-100 pt-5">
                <button type="button" id="btn-prev" class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white px-6 py-3 text-sm font-semibold text-brand-700 transition hover:bg-brand-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </button>
                <button type="button" id="btn-next" class="inline-flex items-center gap-2 rounded-full bg-brand-700 px-7 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-brand-800">
                    Lanjut
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
                <button type="submit" id="btn-submit" class="hidden rounded-full bg-gold-500 px-7 py-3 text-sm font-semibold text-brand-950 shadow-lg transition hover:bg-gold-400">🌸 Kirim Konsultasi</button>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
    (function () {
        const steps = {!! $stepsJson !!};
        const STORAGE_KEY = 'dianmustika_consultation_draft';
        let current = 0;
        const totalSteps = steps.length;
        const totalFieldsets = totalSteps + 1; // +1 untuk review

        // ---- state storage ----
        const placeholder = {
            'name': '...', 'phone': '...', 'instagram': '...', 'address': '...',
        };

        function loadState() {
            try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || {}; }
            catch (e) { return {}; }
        }
        function saveState(s) {
            try { localStorage.setItem(STORAGE_KEY, JSON.stringify(s)); } catch (e) {}
        }

        // ---- condition evaluation ----
        function getValue(state, key) {
            const v = state[key];
            if (Array.isArray(v)) return v;
            return v !== undefined && v !== null ? String(v) : '';
        }
        function evalCondition(cond, state) {
            if (!cond) return true;
            const { field, operator, value } = cond;
            const actual = getValue(state, field);
            if (operator === 'equals') return actual === value;
            if (operator === 'in') return (Array.isArray(value) ? value : []).includes(actual);
            return true;
        }
        function isVisible(field, state) {
            const cond = field.condition;
            if (cond) {
                // chain: if any referenced field depends on another, evaluate sequentially up to chain
                return evalCondition(cond, state);
            }
            return true;
        }

        // ---- collect answers from DOM ----
        function collectField(state, field) {
            const type = field.type;
            // 1. Handle checkbox DULU (pakai name dengan [])
            if (type === 'checkbox') {
                const checked = Array.from(
                    document.querySelectorAll('[name="' + field.key + '[]"]:checked')
                ).map(c => c.value);
                state[field.key] = checked;
                return;
            }
            // 2. Tipe lain pakai selector biasa
            const el = document.querySelector('[name="' + field.key + '"]');
            if (!el) return;
            if (type === 'checkbox_single') {
                state[field.key] = el.checked ? '1' : '';
            } else {
                state[field.key] = el.value;
            }
        }
        function collectStep(stepIndex) {
            const state = loadState();
            const step = steps[stepIndex];
            step.fields.forEach(f => collectField(state, f));
            saveState(state);
            renderVisibility(stepIndex);
            return state;
        }

        // Collect directly from DOM for validation (bypasses localStorage round-trip)
        function collectStepDOM(stepIndex) {
            const state = {};
            const step = steps[stepIndex];
            step.fields.forEach(f => collectField(state, f));
            return state;
        }

        // ---- render visibility for a step based on its conditional fields ----
        function renderVisibility(stepIndex) {
            const state = loadState();
            const step = steps[stepIndex];
            const container = document.querySelector('.step[data-step="' + stepIndex + '"]');
            if (!container) return;
            step.fields.forEach(f => {
                const q = container.querySelector('.question[data-question="' + f.key + '"]');
                if (!q) return;
                const visible = isVisible(f, state);
                q.style.display = visible ? '' : 'none';
                // warning box
                if (q.dataset.warning) {
                    const warn = q.querySelector('.warning-box');
                    if (warn) {
                        const v = getValue(state, f.key);
                        const concern = ['kurang_lancar','belum_lancar','penuh_keras','tidak_nyaman'].includes(v);
                        warn.classList.toggle('hidden', !concern);
                    }
                }
                // others textarea
                if (q.dataset.othersTextarea) {
                    try {
                        const cfg = JSON.parse(q.dataset.othersTextarea);
                        const box = q.querySelector('.others-box');
                        if (box) {
                            const vals = getValue(state, f.key);
                            const show = (Array.isArray(vals) ? vals : []).includes(cfg.when);
                            box.classList.toggle('hidden', !show);
                        }
                    } catch (e) {}
                }
            });
        }

        // ---- progress ----
        function updateProgress() {
            const pct = Math.round((current / totalFieldsets) * 100);
            document.getElementById('progress-bar').style.width = pct + '%';
            document.getElementById('step-percent').textContent = pct + '%';
            if (current === totalSteps) {
                document.getElementById('step-label').textContent = 'Review & Persetujuan';
            } else {
                document.getElementById('step-label').textContent =
                    'Langkah ' + (current + 1) + ' dari ' + totalSteps + ' — ' + steps[current].title;
            }
            document.querySelectorAll('[data-dot]').forEach((d, i) => {
                d.className = 'h-2 flex-1 rounded-full transition ' + (i <= current ? 'bg-brand-400' : 'bg-brand-100');
            });
        }

        // ---- navigation ----
        function showStep(idx) {
            current = idx;
            document.querySelectorAll('.step').forEach(f => f.hidden = true);
            const target = document.querySelector('.step[data-step="' + idx + '"]');
            if (target) target.hidden = false;
            if (idx === totalSteps) {
                document.getElementById('btn-prev').style.display = '';
                document.getElementById('btn-next').style.display = 'none';
                document.getElementById('btn-submit').style.display = '';
                renderReview();
            } else {
                document.getElementById('btn-prev').style.display = idx === 0 ? 'none' : '';
                document.getElementById('btn-next').style.display = '';
                document.getElementById('btn-submit').style.display = 'none';
            }
            updateProgress();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function validateStep(idx, stateOverride) {
            const state = stateOverride || loadState();
            const step = steps[idx];
            let valid = true;
            const container = document.querySelector('.step[data-step="' + idx + '"]');
            step.fields.forEach(f => {
                const q = container.querySelector('.question[data-question="' + f.key + '"]');
                if (!q) return;
                if (!isVisible(f, state)) {
                    q.style.display = 'none';
                    return;
                }
                const err = q.querySelector('.field-error');
                let ok = true;
                let val = getValue(state, f.key);
                if (Array.isArray(val)) val = val.filter(Boolean).join(',');
                if (f.required) {
                    ok = val.trim() !== '';
                }
                if (ok && f.type === 'tel' && val) {
                    ok = /^[0-9+\-\s]{9,16}$/.test(val);
                    if (!ok && err) err.textContent = 'Nomor WhatsApp tidak valid.';
                }
                if (ok && f.type === 'number' && val) {
                    ok = !isNaN(parseFloat(val)) && parseFloat(val) >= 0;
                    if (!ok && err) err.textContent = 'Mohon isi angka yang valid.';
                }
                if (!ok) { valid = false; }
                if (err) err.classList.toggle('hidden', ok);
                if (err) {
                    if (f.type === 'tel' && val && !/^[0-9+\-\s]{9,16}$/.test(val)) {
                        err.textContent = 'Nomor WhatsApp tidak valid.';
                    } else {
                        err.textContent = 'Mohon isi jawaban ini.';
                    }
                    err.classList.toggle('hidden', ok);
                }
            });
            return valid;
        }

        // ---- review ----
        const fieldMeta = {};
        steps.forEach(s => s.fields.forEach(f => { fieldMeta[f.key] = f; }));

        function labelFor(field) {
            return field.label;
        }
        function fmtValue(field, value) {
            if (value === undefined || value === null || value === '') return '<span class="text-ink/40">— tidak diisi —</span>';
            if (field.type === 'checkbox' && Array.isArray(value)) {
                if (value.length === 0) return '<span class="text-ink/40">— tidak diisi —</span>';
                const lab = {};
                (field.options || []).forEach(o => lab[o.value] = o.label);
                return value.map(v => lab[v] || v).join(', ');
            }
            if (field.type === 'select' || field.type === 'radio') {
                const opt = (field.options || []).find(o => o.value === value);
                return opt ? opt.label : value;
            }
            return value;
        }

        function cleanReviewValue(v) {
            if (v === undefined || v === null) return '';
            if (Array.isArray(v)) return v.filter(Boolean);
            return String(v).trim();
        }

        function renderReview() {
            const state = loadState();
            const body = document.getElementById('review-body');
            body.innerHTML = '';
            steps.forEach((step, si) => {
                const section = document.createElement('div');
                section.className = 'overflow-hidden rounded-2xl border border-brand-100';
                const head = document.createElement('div');
                head.className = 'flex items-center justify-between bg-brand-50/70 px-4 py-3';
                head.innerHTML = '<h3 class="font-display text-sm font-semibold text-brand-800">' + step.title + '</h3>' +
                    '<button type="button" class="text-xs font-semibold text-brand-600 hover:text-brand-800" data-goto="' + si + '">Edit</button>';
                section.appendChild(head);
                const list = document.createElement('div');
                list.className = 'px-4 py-3 space-y-1.5';
                let hasAny = false;
                step.fields.forEach(f => {
                    if (!isVisible(f, state)) return;
                    const v = cleanReviewValue(state[f.key]);
                    if (Array.isArray(v) ? v.length === 0 : v === '') return;
                    hasAny = true;
                    const row = document.createElement('div');
                    row.className = 'flex justify-between gap-3 text-sm';
                    row.innerHTML = '<span class="text-ink/60">' + labelFor(f) + '</span><span class="text-right font-medium text-ink/90">' + fmtValue(f, state[f.key]) + '</span>';
                    list.appendChild(row);
                });
                if (!hasAny) {
                    list.innerHTML = '<p class="text-xs text-ink/40">Tidak ada jawaban diisi.</p>';
                }
                section.appendChild(list);
                body.appendChild(section);
            });
            // listeners
            body.querySelectorAll('[data-goto]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const idx = parseInt(btn.dataset.goto, 10);
                    showStep(idx);
                });
            });
        }

        // submit
        function validateConsent() {
            const t = document.getElementById('consent-truth').checked;
            const m = document.getElementById('consent-medical').checked;
            const err = document.getElementById('consent-error');
            if (!t || !m) { err.classList.remove('hidden'); return false; }
            err.classList.add('hidden');
            return true;
        }

        // wire form
        const form = document.getElementById('consultation-form');
        const dataJson = document.getElementById('data-json');
        const consented = document.getElementById('consented');

        document.getElementById('btn-next').addEventListener('click', () => {
            collectStep(current); // persist to localStorage
            const freshState = collectStepDOM(current); // validate from DOM directly
            if (!validateStep(current, freshState)) return;
            showStep(current + 1);
        });
        document.getElementById('btn-prev').addEventListener('click', () => {
            // last real step is totalSteps-1; from review go to last step
            if (current === totalSteps) { showStep(totalSteps - 1); return; }
            if (current > 0) { collectStep(current); showStep(current - 1); }
        });
        document.getElementById('btn-submit').addEventListener('click', (e) => {
            e.preventDefault();
            collectStep(totalSteps - 1);
            if (!validateConsent()) return;
            const state = loadState();
            // strip empties
            Object.keys(state).forEach(k => {
                if (Array.isArray(state[k])) state[k] = state[k].filter(Boolean);
            });
            dataJson.value = JSON.stringify(state);
            consented.value = '1';
            // remove non-answer keys already collected; the backend uses data_json. Clear individual fields to avoid duplicate? Backend uses data_json only.
            form.submit();
        });

        // live listeners to update state
        document.addEventListener('change', (e) => {
            if (e.target && (e.target.classList.contains('q-input') || e.target.classList.contains('q-checkbox') || e.target.classList.contains('q-radio'))) {
                collectStep(currentForElement(e.target));
            }
        });
        document.addEventListener('input', (e) => {
            if (e.target && e.target.classList.contains('q-input')) {
                collectStep(currentForElement(e.target));
            }
        });
        function currentForElement(el) {
            const fieldset = el.closest('.step');
            return fieldset ? parseInt(fieldset.dataset.step, 10) : current;
        }

        // init
        showStep(0);
        for (let i = 0; i < totalSteps; i++) renderVisibility(i);
    })();
    </script>
    @endpush
</x-layouts.app>
