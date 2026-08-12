@props(['testimonial'])

<figure class="flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-ink/5">
    <div class="flex gap-1 text-gold-500" aria-label="Rating {{ $testimonial->rating }} dari 5">
        @for ($i = 1; $i <= 5; $i++)
            <svg class="{{ $i <= $testimonial->rating ? 'text-gold-500' : 'text-ink/15' }} h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 0 0 .95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.446a1 1 0 0 0-.363 1.118l1.286 3.958c.3.921-.755 1.688-1.539 1.118l-3.367-2.446a1 1 0 0 0-1.175 0l-3.367 2.446c-.783.57-1.838-.197-1.538-1.118l1.285-3.958a1 1 0 0 0-.363-1.118L2.062 9.385c-.782-.57-.38-1.81.588-1.81h4.162a1 1 0 0 0 .95-.69l1.287-3.958z"/>
            </svg>
        @endfor
    </div>
    <blockquote class="mt-4 flex-1 text-sm leading-relaxed text-ink/80">
        "{{ $testimonial->content }}"
    </blockquote>
    <figcaption class="mt-5 flex items-center gap-3 border-t border-ink/5 pt-4">
        @if ($testimonial->image)
            <img src="{{ asset('storage/'.$testimonial->image) }}" alt="{{ $testimonial->customer_name }}" loading="lazy" class="h-11 w-11 rounded-full object-cover ring-2 ring-brand-100">
        @else
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-100 font-display text-lg font-semibold text-brand-700 ring-2 ring-brand-100">{{ $testimonial->customer_name[0] ?? 'P' }}</span>
        @endif
        <div>
            <div class="text-sm font-semibold text-brand-800">{{ $testimonial->customer_name }}</div>
            @if ($testimonial->treatment)
                <div class="text-xs text-ink/50">Perawatan: {{ $testimonial->treatment }}</div>
            @endif
        </div>
    </figcaption>
</figure>
