<x-layouts.app :seo="$seo">

    <x-sections.page-hero title="FAQ" description="Pertanyaan yang sering diajukan seputar layanan Dian Mustika." />

    <section class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        @php
            $labels = [
                'umum' => 'Umum',
                'layanan' => 'Layanan',
                'harga' => 'Harga & Pembayaran',
                'lokasi' => 'Lokasi',
                'perawatan' => 'Perawatan',
            ];
        @endphp

        @forelse ($grouped as $category => $items)
            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-brand-800">{{ $labels[$category] ?? ucfirst($category) }}</h2>
                <div class="mt-4 space-y-3">
                    @foreach ($items as $faq)
                        <x-cards.faq-item :faq="$faq" />
                    @endforeach
                </div>
            </div>
        @empty
            <p class="py-16 text-center text-ink/60">Belum ada FAQ.</p>
        @endforelse

        <div class="mt-6 rounded-3xl bg-white p-8 text-center ring-1 ring-ink/5">
            <h2 class="font-display text-xl font-semibold text-brand-800">Pertanyaan lain?</h2>
            <p class="mt-2 text-sm text-ink/60">Jangan ragu untuk bertanya langsung kepada kami melalui WhatsApp.</p>
            <a href="{{ App\Services\WhatsAppService::url('Halo Dian Mustika, saya memiliki pertanyaan.') }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-2 rounded-full bg-[#25D366] px-7 py-3 text-sm font-semibold text-white shadow-lg transition hover:brightness-95">
                Tanya via WhatsApp
            </a>
        </div>
    </section>

    <x-sections.cta />
</x-layouts.app>
