@if($media->isEmpty())
    @if(request('q') || request('type','all')!='all')
        <p class="text-center text-muted py-4">Tidak ada media yang cocok dengan pencarian.</p>
    @else
        <p class="text-center text-muted py-4">Belum ada media. Upload gambar pertama.</p>
    @endif
@else
    <div class="row">
        @foreach($media as $m)
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="d-flex align-items-center justify-content-center bg-light" style="height: 150px; overflow: hidden;">
                        @if($m->isImage())
                            <img src="{{ asset('storage/'.$m->file_path) }}" alt="{{ $m->alt_text }}" style="max-height: 150px; max-width: 100%; object-fit: contain;">
                        @else
                            <div class="text-center">
                                <i class="{{ $m->fileIcon() }} fa-4x"></i>
                                <span class="badge badge-secondary d-block mt-2 text-uppercase" style="font-size: 10px;">{{ $m->extension() }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body p-2">
                        <p class="small font-weight-bold mb-1 text-truncate" title="{{ $m->original_name }}">{{ \App\Support\MediaHelper::title($m->original_name) }}</p>
                        <small class="text-muted d-block">{{ $m->width ? $m->width.'×'.$m->height.' · ' : '' }}{{ number_format($m->size/1024, 1) }} KB</small>
                        @if($m->thumbnail_path)
                            <small class="text-success d-block"><i class="fas fa-check mr-1"></i>Thumb 300 & 800</small>
                        @endif
                        <div class="mt-2 d-flex flex-wrap" style="gap: 4px;">
                            <a href="{{ asset('storage/'.$m->file_path) }}" download target="_blank" class="btn btn-success btn-xs" title="Unduh"><i class="fas fa-download"></i></a>
                            <button type="button" class="btn btn-primary btn-xs btn-copy-url" data-url="{{ asset('storage/'.$m->file_path) }}"><i class="fas fa-link"></i></button>
                            <button type="button" class="btn btn-info btn-xs btn-pick-media" data-path="{{ $m->file_path }}" data-url="{{ asset('storage/'.$m->file_path) }}" data-alt="{{ $m->alt_text }}" title="Pilih"><i class="fas fa-check"></i> Pilih</button>
                            <form method="POST" action="{{ route('admin.media.destroy', $m) }}" class="d-inline" onsubmit="return confirm('Hapus media ini? File tetap jika masih dipakai di konten.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer p-1">
                        <small class="text-muted">{{ $m->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex flex-wrap align-items-center justify-content-between mt-3" style="gap: 8px;">
        <small class="text-muted">Menampilkan {{ $media->firstItem() }} - {{ $media->lastItem() }} dari total {{ $media->total() }} media</small>
        <div>
            {{ $media->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endif
