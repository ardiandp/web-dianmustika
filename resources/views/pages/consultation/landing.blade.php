<x-layouts.app :seo="$seo">

    <section class="relative overflow-hidden bg-brand-950">
        <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 15% 20%, #e3c98e 0, transparent 30%), radial-gradient(circle at 85% 75%, #dca8ae 0, transparent 35%);"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-gold-400">Dian Mustika</p>
                <h1 class="mt-4 font-display text-4xl font-semibold text-cream sm:text-5xl">Konsultasi Homecare Pascamelahirkan</h1>
                <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-brand-100/80">
                    Kami membantu memahami kebutuhan perawatan Anda sebelum treatment.
                </p>
                <ul class="mx-auto mt-8 grid max-w-xl grid-cols-1 gap-3 text-left sm:grid-cols-2">
                    <li class="flex items-center gap-3 text-cream">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold-500 text-brand-950"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                        Perawatan Ibu
                    </li>
                    <li class="flex items-center gap-3 text-cream">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold-500 text-brand-950"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                        Perawatan Bayi
                    </li>
                    <li class="flex items-center gap-3 text-cream">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold-500 text-brand-950"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                        Body Treatment
                    </li>
                    <li class="flex items-center gap-3 text-cream">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gold-500 text-brand-950"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                        Breastcare &amp; Pijat Laktasi
                    </li>
                </ul>
                <div class="mt-10">
                    <a href="{{ route('consultation.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-gold-500 px-8 py-4 text-sm font-semibold text-brand-950 shadow-lg transition hover:bg-gold-400">
                        Mulai Konsultasi
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </a>
                </div>
                <p class="mx-auto mt-8 max-w-xl text-xs leading-relaxed text-brand-100/60">
                    Proses konsultasi membutuhkan beberapa menit. Mohon isi informasi dengan sebenar-benarnya agar tim Dian Mustika dapat memahami kebutuhan perawatan Anda.
                </p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-700"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <h3 class="mt-4 font-display text-lg font-semibold text-brand-800">Mudah Diisi</h3>
                <p class="mt-1 text-sm leading-relaxed text-ink/60">Form bertahap yang nyaman diisi dari smartphone, hanya dalam beberapa menit.</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-700"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
                <h3 class="mt-4 font-display text-lg font-semibold text-brand-800">Pertanyaan Relevan</h3>
                <p class="mt-1 text-sm leading-relaxed text-ink/60">Hanya pertanyaan yang relevan dengan kondisi Anda yang akan ditampilkan.</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-700"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 9.75h4.5m-4.5 4.5h4.5M19.5 12v7.5A1.5 1.5 0 0118 21H6a1.5 1.5 0 01-1.5-1.5V4.5A1.5 1.5 0 016 3h5.25a3.375 3.375 0 013.375 3.375v1.5A1.125 1.125 0 0015.75 9h1.5a3.375 3.375 0 013.375 3.375z"/></svg></div>
                <h3 class="mt-4 font-display text-lg font-semibold text-brand-800">Dibantu Tim Kami</h3>
                <p class="mt-1 text-sm leading-relaxed text-ink/60">Tim kami akan mengonfirmasi dan menentukan treatment yang paling sesuai untuk Anda.</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-700"><svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487z"/></svg></div>
                <h3 class="mt-4 font-display text-lg font-semibold text-brand-800">Data Terkelola Baik</h3>
                <p class="mt-1 text-sm leading-relaxed text-ink/60">Data Anda tersimpan rapi sehingga kunjungan berikutnya lebih mudah dilacak.</p>
            </div>
        </div>
    </section>

    <x-sections.cta title="Siap Memulai Perjalanan Perawatan Anda?" description="Konsultasikan kebutuhan perawatan pascamelahirkan Anda dengan tim kami." />
</x-layouts.app>
