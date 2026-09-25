@php
    $chatEnabled = (bool) App\Models\Setting::get('chat_ai_enabled', config('ai-chat.enabled'));
    $chatWelcome = (string) App\Models\Setting::get('chat_ai_welcome', config('ai-chat.welcome'));
@endphp

@if ($chatEnabled)
<div
    class="fixed bottom-5 right-5 z-40 flex flex-col items-end"
    x-data="aiChat()"
>
    {{-- Panel --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-3 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-3 scale-95"
        class="mb-3 flex h-[500px] max-h-[70vh] w-[340px] max-w-[calc(100vw-2.5rem)] flex-col overflow-hidden rounded-2xl border border-brand-100 bg-white shadow-2xl shadow-black/20"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between bg-brand-700 px-4 py-3 text-white">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 2a2.5 2.5 0 0 1 2.45 2h.05a4.5 4.5 0 0 1 4.5 4.5V10a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H16v1a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-1H5a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2v-1.5A4.5 4.5 0 0 1 9.5 4h.05A2.5 2.5 0 0 1 12 2Zm0 2a.5.5 0 0 0-.5.5V5h1v-.5A.5.5 0 0 0 12 4ZM8.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Zm7 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3ZM9 15a4 4 0 0 1 6 0l-1.2 1.2a2.4 2.4 0 0 0-3.6 0L9 15Z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold leading-tight">Mika — Asisten AI</p>
                    <p class="flex items-center gap-1 text-xs text-white/80">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-green-300"></span>
                        Online
                    </p>
                </div>
            </div>
            <button type="button" class="rounded-full p-1.5 text-white/80 transition hover:bg-white/20 hover:text-white" @click="open = false" aria-label="Tutup chat">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div x-ref="messages" class="flex-1 space-y-3 overflow-y-auto bg-cream px-3 py-4" :class="{ 'flex flex-col justify-end': messages.length === 0 }">
            <template x-for="(msg, i) in messages" :key="i">
                <div class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                    <div
                        class="max-w-[85%] rounded-2xl px-3.5 py-2 text-sm leading-relaxed"
                        :class="msg.role === 'user'
                            ? 'whitespace-pre-wrap bg-brand-600 text-white rounded-br-sm'
                            : 'ai-chat-md border border-brand-100 bg-white text-ink rounded-bl-sm'"
                        x-html="renderMessage(msg)"
                    ></div>
                </div>
            </template>

            {{-- Typing indicator --}}
            <div x-show="loading" class="flex justify-start">
                <div class="flex items-center gap-1 rounded-2xl rounded-bl-sm border border-brand-100 bg-white px-4 py-3">
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-brand-300"></span>
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-brand-400" style="animation-delay: 0.15s"></span>
                    <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-brand-500" style="animation-delay: 0.3s"></span>
                </div>
            </div>
        </div>

        {{-- Quick suggestions --}}
        <div class="flex flex-wrap gap-2 border-t border-brand-100 bg-white px-3 pb-2 pt-2" x-show="!loading">
            <template x-for="chip in suggestions" :key="chip">
                <button
                    type="button"
                    class="rounded-full border border-brand-200 bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700 transition hover:bg-brand-100"
                    @click="suggest(chip)"
                    x-text="chip"
                ></button>
            </template>
        </div>

        {{-- Input --}}
        <div class="flex items-center gap-2 border-t border-brand-100 bg-white px-3 py-3">
            <textarea
                x-model="input"
                rows="1"
                placeholder="Tulis pertanyaan tentang layanan, promo, atau lokasi..."
                class="max-h-24 flex-1 resize-none rounded-xl border-brand-200 bg-cream px-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500"
                @keydown.enter.prevent="submit()"
            ></textarea>
            <button
                type="button"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-700 text-white transition hover:bg-brand-800 disabled:opacity-50"
                :disabled="loading || input.trim() === ''"
                @click="submit()"
                aria-label="Kirim pesan"
            >
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3.4 20.4 20.85 12.9c.4-.19.4-.79 0-.98L3.4 3.6c-.5-.24-1.06.14-1.03.7l.28 5.06c.02.38.32.68.7.71l10.54.86c.15.01.24.19.15.32-.07.09-.19.13-.31.11l-7.93-.73c-.4-.04-.73.28-.73.68v2.42c0 .23.16.42.38.47l5.5.87c.27.04.44.3.39.57-.04.22-.21.39-.43.43l-6.36 1.02a.64.64 0 0 0-.54.64v2.2c0 .56.53.94 1.03.7Z"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Toggle button --}}
    <button
        type="button"
        class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-700 text-white shadow-lg shadow-black/20 transition hover:scale-105 hover:bg-brand-800"
        @click="open = !open"
        :aria-label="open ? 'Tutup chat AI' : 'Buka chat AI'"
    >
        <svg x-show="!open" class="h-7 w-7" x-cloak fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M6 14h2v-1h2v1h6v-2H8V9H6v5Zm-4 8V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H8l-6 4Zm2-4.1L5.3 16H18V8H4v9.9Z"/>
        </svg>
        <svg x-show="open" class="h-6 w-6" x-cloak fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>

<script>
function aiChat() {
    return {
        open: false,
        loading: false,
        messages: [],
        input: '',
        session: '',
        suggestions: ['Daftar layanan', 'Promo sedang berjalan', 'Cara reservasi', 'Jam operasional'],
        welcome: @js($chatWelcome),
        init() {
            try {
                this.session = localStorage.getItem('dian.ai.session') || '';
            } catch (e) {}
            this.$watch('open', () => { if (this.open) this.$nextTick(() => this.scrollToBottom()); });
        },
        submit() {
            const text = this.input.trim();
            if (!text || this.loading) return;

            this.messages.push({ role: 'user', content: text });
            this.input = '';
            this.loading = true;
            this.$nextTick(() => this.scrollToBottom());

            const history = this.messages.slice(-6).map((m) => ({ role: m.role, content: m.content }));

            fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ message: text, session: this.session, history }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.session) {
                        this.session = data.session;
                        try { localStorage.setItem('dian.ai.session', data.session); } catch (e) {}
                    }
                    this.messages.push({ role: 'assistant', content: data.reply || 'Maaf, terjadi kendala. Silakan coba lagi.' });
                })
                .catch(() => {
                    this.messages.push({ role: 'assistant', content: 'Maaf, gangguan koneksi. Silakan coba lagi sebentar lagi.' });
                })
                .finally(() => {
                    this.loading = false;
                    this.$nextTick(() => this.scrollToBottom());
                });
        },
        suggest(question) {
            this.input = question;
            this.submit();
        },
        scrollToBottom() {
            const el = this.$refs.messages;
            if (el) el.scrollTop = el.scrollHeight;
        },
        renderMessage(msg) {
            if (msg.role === 'user') {
                return window.aiEscapeHtml ? window.aiEscapeHtml(msg.content) : msg.content;
            }
            return window.aiRenderMarkdown ? window.aiRenderMarkdown(msg.content) : msg.content;
        },
    };
}
</script>
@endif