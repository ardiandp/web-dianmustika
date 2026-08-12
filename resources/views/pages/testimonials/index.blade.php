<x-layouts.app :seo="$seo">

    <x-sections.page-hero title="Testimoni Pelanggan" description="Kepercayaan pelanggan adalah kebanggaan kami." />

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($testimonials as $testimonial)
                <x-cards.testimonial-card :testimonial="$testimonial" />
            @empty
                <p class="col-span-full py-16 text-center text-ink/60">Belum ada testimoni.</p>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $testimonials->links() }}
        </div>
    </section>

    <x-sections.cta title="Rasakan Sendiri Pengalaman Kami" description="Konsultasikan kebutuhan perawatan Anda dan rasakan pelayanan terbaik dari Dian Mustika." />
</x-layouts.app>
