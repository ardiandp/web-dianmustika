<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Location;
use App\Models\Package;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiChatService
{
    /**
     * Apakah fitur chat AI diaktifkan.
     */
    public function enabled(): bool
    {
        return (bool) Setting::get('chat_ai_enabled', config('ai-chat.enabled'));
    }

    /**
     * Kirim pesan pengguna ke Ollama dan kembalikan teks balasan.
     */
    public function chat(string $message, array $history = []): string
    {
        $messages = $this->buildMessages($message, $history);

        try {
            $response = Http::timeout(config('ai-chat.timeout', 180))
                ->acceptJson()
                ->post(rtrim((string) config('ai-chat.url'), '/').'/api/chat', [
                    'model' => config('ai-chat.model'),
                    'messages' => $messages,
                    'stream' => false,
                    'options' => [
                        'temperature' => config('ai-chat.temperature', 0.7),
                        'num_predict' => config('ai-chat.max_tokens', 512),
                    ],
                ]);

            if ($response->successful()) {
                $content = $response->json('message.content');

                if (is_string($content) && trim($content) !== '') {
                    return $this->clean($content);
                }
            } else {
                Log::warning('AI chat: respons non-2xx.', [
                    'status' => $response->status(),
                    'body' => mb_substr((string) $response->body(), 0, 500),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('AI chat: koneksi ke Ollama gagal.', ['error' => $e->getMessage()]);
        }

        return $this->fallback();
    }

    /**
     * Pesan sambutan dari pengaturan (atau fallback config).
     */
    public function welcome(): string
    {
        return (string) Setting::get('chat_ai_welcome', config('ai-chat.welcome'));
    }

    /**
     * Balasan ramah saat AI sedang tidak tersedia.
     */
    public function fallback(): string
    {
        $whatsapp = WhatsAppService::url('Halo Dian Mustika, saya ingin bertanya tentang layanan / promo.');

        return 'Maaf, asisten AI sedang sibuk. Silakan hubungi kami melalui WhatsApp ('.$whatsapp.') atau telepon '.((string) Setting::get('phone')).' untuk informasi lebih lanjut.';
    }

    protected function buildMessages(string $message, array $history = []): array
    {
        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt($message)],
        ];

        $maxTurns = (int) config('ai-chat.history_turns', 6);
        foreach (array_slice($history, -$maxTurns) as $entry) {
            $role = (($entry['role'] ?? '') === 'assistant') ? 'assistant' : 'user';
            $content = trim((string) ($entry['content'] ?? ''));

            if ($content !== '') {
                $messages[] = ['role' => $role, 'content' => mb_substr($content, 0, 2000)];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        return $messages;
    }

    protected function systemPrompt(string $message): string
    {
        $replacer = [
            '{COMPANY_NAME}' => (string) Setting::get('site_name', config('app.name')),
            '{COMPANY_INFO}' => $this->companyInfo(),
            '{KNOWLEDGE}' => $this->contextFor($message),
            '{WHATSAPP}' => (string) Setting::get('whatsapp'),
            '{PHONE}' => (string) Setting::get('phone'),
        ];

        return strtr((string) config('ai-chat.system_prompt'), $replacer);
    }

    /**
     * Informasi umum perusahaan dari pengaturan.
     */
    protected function companyInfo(): string
    {
        $settings = Setting::allCached();

        $lines = [
            'Nama: '.($settings['site_name'] ?? config('app.name')),
        ];

        if (filled($settings['site_tagline'] ?? null)) {
            $lines[] = 'Tagline: '.$settings['site_tagline'];
        }
        if (filled($settings['site_description'] ?? null)) {
            $lines[] = 'Deskripsi: '.Str::of((string) $settings['site_description'])->stripTags()->squish()->limit(260)->toString();
        }
        if (filled($settings['address'] ?? null)) {
            $lines[] = 'Alamat: '.$settings['address'];
        }
        if (filled($settings['phone'] ?? null)) {
            $lines[] = 'Telepon: '.$settings['phone'];
        }
        if (filled($settings['whatsapp'] ?? null)) {
            $lines[] = 'WhatsApp: '.$settings['whatsapp'];
        }
        if (filled($settings['email'] ?? null)) {
            $lines[] = 'Email: '.$settings['email'];
        }

        $hours = Setting::get('opening_hours');
        if (is_array($hours) && count($hours) > 0) {
            $text = array_map(fn ($day, $time) => $day.': '.$time, array_keys($hours), array_values($hours));
            $lines[] = 'Jam operasional: '.implode('; ', $text);
        }

        return implode("\n", $lines);
    }

    /**
     * Seluruh sumber pengetahuan (layanan, paket, promo, FAQ, lokasi, artikel).
     */
    protected function sources(): array
    {
        return Cache::remember('ai_chat.knowledge.v2', 3600, function () {
            return $this->buildSources();
        });
    }

    protected function buildSources(): array
    {
        $sources = [];
        $enabled = (array) Setting::get('chat_ai_sources', config('ai-chat.sources', ['layanan', 'paket', 'faq', 'lokasi', 'artikel']));

        if (in_array('layanan', $enabled, true)) {
            foreach (Service::active()->ordered()->with('category')->get() as $service) {
                $description = $service->short_description
                    ?: Str::of((string) $service->description)->stripTags()->limit(180)->toString();

                $sources[] = [
                    'type' => 'layanan',
                    'title' => $service->name.($service->displayPrice() ? ' ('.$service->displayPrice().')' : ''),
                    'text' => ($service->category?->name ? $service->category->name.'. ' : '').$description,
                ];
            }
        }

        if (in_array('paket', $enabled, true)) {
            foreach (Package::active()->ordered()->get() as $package) {
                $normalPrice = $package->price !== null
                    ? 'Rp '.number_format((float) $package->price, 0, ',', '.')
                    : null;

                if ($package->hasPromo() && $package->promo_price !== null) {
                    $promoPrice = 'Rp '.number_format((float) $package->promo_price, 0, ',', '.');
                    $period = '';
                    if ($package->starts_at) {
                        $period .= ' mulai '.$package->starts_at->format('d M Y');
                    }
                    if ($package->ends_at) {
                        $period .= ' s/d '.$package->ends_at->format('d M Y');
                    }

                    $sources[] = [
                        'type' => 'promo',
                        'title' => $package->name,
                        'text' => 'PROMO BERLAKU: '.$promoPrice.' (harga normal '.($normalPrice ?? '?').')'.$period.'. '.Str::of((string) $package->description)->stripTags()->limit(160)->toString(),
                    ];
                } else {
                    $sources[] = [
                        'type' => 'paket',
                        'title' => $package->name,
                        'text' => ($normalPrice ? $normalPrice.'. ' : '').Str::of((string) $package->description)->stripTags()->limit(160)->toString(),
                    ];
                }
            }
        }

        if (in_array('faq', $enabled, true)) {
            foreach (Faq::active()->ordered()->limit(50)->get() as $faq) {
                $sources[] = [
                    'type' => 'faq',
                    'title' => $faq->question,
                    'text' => Str::of((string) $faq->answer)->stripTags()->limit(300)->toString(),
                ];
            }
        }

        if (in_array('lokasi', $enabled, true)) {
            foreach (Location::active()->ordered()->get() as $location) {
                $phones = array_filter([$location->phone, $location->whatsapp]);
                $hours = is_array($location->opening_hours) && count($location->opening_hours) > 0
                    ? ' Jam: '.implode('; ', array_map(fn ($d, $t) => $d.': '.$t, array_keys($location->opening_hours), array_values($location->opening_hours)))
                    : '';

                $sources[] = [
                    'type' => 'lokasi',
                    'title' => $location->name,
                    'text' => 'Alamat: '.$location->address
                        .($phones ? '. Kontak: '.implode(' / ', $phones) : '')
                        .$hours,
                ];
            }
        }

        if (in_array('artikel', $enabled, true)) {
            foreach (Article::active()->published()->ordered()->with('category')->limit(20)->get() as $article) {
                $excerpt = $article->excerpt
                    ?: Str::of((string) $article->content)->stripTags()->limit(240)->toString();

                $sources[] = [
                    'type' => 'artikel',
                    'title' => $article->title,
                    'text' => ($article->category?->name ? $article->category->name.'. ' : '').Str::of((string) $excerpt)->squish()->limit(240)->toString(),
                ];
            }
        }

        return $sources;
    }

    /**
     * Pilih item konteks paling relevan untuk pertanyaan terakhir.
     */
    protected function contextFor(string $query, int $limit = 0): string
    {
        $limit = $limit > 0 ? $limit : (int) config('ai-chat.max_context_items', 8);
        $sources = $this->sources();

        if ($limit === 0 || count($sources) === 0) {
            return '(Tidak ada knowledge layanan/paket/FAQ/lokasi/artikel yang diizinkan saat ini.)';
        }

        if (trim($query) === '') {
            $selected = array_slice($sources, 0, $limit);
        } else {
            usort($sources, fn ($a, $b) => $this->score($query, $b) <=> $this->score($query, $a));
            $selected = array_slice($sources, 0, $limit);
        }

        $lines = array_map(fn ($item) => strtoupper($item['type']).' — '.$item['title'].': '.$item['text'], $selected);

        return implode("\n\n", $lines);
    }

    /**
     * Skor relevansi sederhana berdasarkan overlap token.
     */
    protected function score(string $query, array $item): int
    {
        $tokens = $this->tokens($query);

        if (count($tokens) === 0) {
            return 0;
        }

        $haystack = $this->tokens($item['title'].' '.$item['text']);
        $titleTokens = $this->tokens($item['title']);

        $score = 0;

        foreach ($tokens as $token) {
            if (mb_strlen($token) < 3) {
                continue;
            }

            if (in_array($token, $titleTokens, true)) {
                $score += 5;
            }

            foreach ($haystack as $hay) {
                if ($hay === $token) {
                    $score += 3;
                } elseif (mb_strlen($hay) > 4 && (str_contains($hay, $token) || str_contains($token, $hay))) {
                    $score += 1;
                }
            }
        }

        return $score;
    }

    protected function tokens(string $text): array
    {
        return preg_split('/[\s\W_]+/u', mb_strtolower((string) $text), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    }

    /**
     * Bersihkan artefak ejaan model (markdown/code fence, prefix percakapan).
     */
    protected function clean(string $content): string
    {
        $content = trim($content);

        if (Str::startsWith(mb_strtolower($content), 'assistant:')) {
            $content = trim(mb_substr($content, 10));
        }

        if (preg_match('/```(?:php|html|json|text)?\s*(.*?)\s*```/is', $content, $m)) {
            $content = trim($m[1]);
        }

        return trim($content);
    }
}
