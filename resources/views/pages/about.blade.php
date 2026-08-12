<x-layouts.app title="Tentang Kami" description="Mengenal lebih dekat Dian Mustika, pusat perawatan tubuh dan kecantikan.">

    <x-sections.page-hero title="Tentang Dian Mustika" description="Pusat perawatan tubuh dan kecantikan yang menggabungkan teknik modern dengan kearifan tradisional." />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
            @php $aboutImage = App\Models\Gallery::active()->where('category', 'tempat')->value('image'); @endphp
            <div class="relative">
                <div class="overflow-hidden rounded-[2rem] shadow-xl">
                    @if ($aboutImage)
                        <img src="{{ asset('storage/'.$aboutImage) }}" alt="Tempat perawatan Dian Mustika" class="h-[400px] w-full object-cover" loading="lazy">
                    @else
                        <div class="flex h-[400px] w-full items-center justify-center bg-brand-100 font-display text-3xl text-brand-700">Dian Mustika</div>
                    @endif
                </div>
            </div>
            <div>
                <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.25em] text-gold-600">
                    <span class="h-px w-6 bg-gold-500/60"></span> Profil Kami
                </span>
                <h2 class="mt-3 font-display text-3xl font-semibold text-brand-800 sm:text-4xl">{{ App\Models\Setting::get('about_heading', 'Tentang Dian Mustika') }}</h2>
                <p class="mt-5 leading-relaxed text-ink/70">{{ App\Models\Setting::get('about_text', '') }}</p>
                <p class="mt-4 leading-relaxed text-ink/70">
                    Dian Mustika lahir dari keyakinan bahwa setiap wanita berhak merasa nyaman dan percaya diri. Dengan suasana yang tenang, terapis yang profesional, serta bahan-bahan alami pilihan, kami berkomitmen memberikan pengalaman perawatan terbaik bagi setiap pelanggan.
                </p>
            </div>
        </div>
    </section>

    {{-- Visi Misi --}}
    <section class="bg-white border-y border-brand-100 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-sections.section-heading title="Visi & Misi" description="Arah dan komitmen kami dalam melayani setiap pelanggan.">
                Nilai Kami
            </x-sections.section-heading>
            <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="rounded-2xl bg-cream p-6 ring-1 ring-brand-100">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-700 text-cream">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                    </span>
                    <h3 class="mt-4 font-display text-xl font-semibold text-brand-800">Visi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink/70">Menjadi pusat perawatan tubuh dan kecantikan terpercaya yang membantu wanita Indonesia merasa sehat, nyaman, dan percaya diri.</p>
                </div>
                <div class="rounded-2xl bg-cream p-6 ring-1 ring-brand-100">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-700 text-cream">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <h3 class="mt-4 font-display text-xl font-semibold text-brand-800">Misi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink/70">Menghadirkan layanan perawatan berkualitas dengan terapis profesional, bahan alami pilihan, dan suasana yang nyaman untuk setiap pelanggan.</p>
                </div>
                <div class="rounded-2xl bg-cream p-6 ring-1 ring-brand-100">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-700 text-cream">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <h3 class="mt-4 font-display text-xl font-semibold text-brand-800">Filosofi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink/70">Merawat diri adalah bentuk menghargai diri sendiri. Kami percaya kenyamanan dan kualitas adalah kunci pengalaman yang berkesan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Layanan Pilihan --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <x-sections.section-heading title="Layanan Kami" description="Beragam perawatan untuk kebutuhan tubuh dan kecantikan Anda.">
            Layanan Pilihan
        </x-sections.section-heading>
        <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($services as $service)
                <x-cards.service-card :service="$service" />
            @empty
                <p class="col-span-full text-center text-ink/60">Belum ada layanan.</p>
            @endforelse
        </div>
    </section>

    <x-sections.cta title="Kenali Kami Lebih Dekat" description="Konsultasikan kebutuhan perawatan Anda dengan tim kami melalui WhatsApp." />
</x-layouts.app>
