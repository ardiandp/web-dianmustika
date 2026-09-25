<x-layouts.admin title="Detail Chat AI">
    <x-admin.page-header title="Detail Sesi Chat" description="Percakapan pengunjung dengan asisten AI." />

    <div class="row">
        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-header"><h3 class="card-title">Informasi Sesi</h3></div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <tbody>
                            <tr><th class="w-40">ID</th><td>#{{ $chat->id }}</td></tr>
                            <tr><th>Token</th><td><code>{{ $chat->session_token }}</code></td></tr>
                            <tr><th>Status</th><td><span class="badge badge-{{ $chat->status === 'active' ? 'success' : 'secondary' }}">{{ $chat->statusLabel() }}</span></td></tr>
                            <tr><th>Jumlah Pesan</th><td>{{ $chat->message_count }}</td></tr>
                            <tr><th>Perangkat</th><td>{{ ucfirst($chat->device ?? '-') }}</td></tr>
                            <tr><th>Browser</th><td>{{ $chat->browser ?? '-' }}</td></tr>
                            <tr><th>Sistem Operasi</th><td>{{ $chat->os ?? '-' }}</td></tr>
                            <tr><th>Mulai</th><td>{{ $chat->created_at?->format('d/m/Y H:i') }}</td></tr>
                            <tr><th>Aktivitas Terakhir</th><td>{{ $chat->last_message_at?->format('d/m/Y H:i') }}</td></tr>
                            <tr><th>IP (hash)</th><td><code>{{ $chat->ip_hash ?? '-' }}</code></td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <form method="POST" action="{{ route('admin.chats.destroy', $chat) }}" class="d-inline" onsubmit="return confirm('Hapus seluruh percakapan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash mr-1"></i>Hapus</button>
                    </form>
                    <a href="{{ route('admin.chats.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header"><h3 class="card-title">Percakapan</h3></div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @forelse ($chat->messages as $message)
                        <div class="d-flex {{ $message->role === 'user' ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                            <div class="px-3 py-2 rounded {{ $message->role === 'user' ? 'bg-primary text-white' : 'bg-light text-dark' }}" style="max-width: 80%; white-space: pre-wrap;">
                                <small class="d-block opacity-75 mb-1">
                                    {{ $message->role === 'user' ? 'Pengunjung' : 'AI (Mika)' }} · {{ $message->created_at?->format('d/m/Y H:i') }}
                                </small>
                                {!! nl2br(e($message->content)) !!}
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted mb-0">Belum ada pesan pada sesi ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>