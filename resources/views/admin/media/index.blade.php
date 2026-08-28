<x-layouts.admin title="Media Library">
    <x-admin.page-header title="Media Library" description="WordPress-like: 1 gambar bisa dipakai berkali-kali di semua halaman. Upload sekali, pilih berkali-kali. Deduplikasi otomatis via hash + thumbnail 300 & 800." />

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-cloud-upload-alt mr-1"></i> Upload Media</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="row align-items-end">
                @csrf
                <div class="col-md-5">
                    <div class="form-group mb-0">
                        <label>Pilih Gambar</label>
                        <div class="custom-file">
                            <input type="file" name="file" id="file" accept="image/*" class="custom-file-input" required>
                            <label class="custom-file-label" for="file">Pilih file...</label>
                        </div>
                        <small class="form-text text-muted">JPG, PNG, WebP, SVG. Maks 5MB. Duplikat otomatis pakai file existing.</small>
                        @error('file')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label>Alt Text</label>
                        <input type="text" name="alt_text" class="form-control" placeholder="Alt text (opsional)">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label>Caption</label>
                        <input type="text" name="caption" class="form-control" placeholder="Caption (opsional)">
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-upload mr-1"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-images mr-1"></i> Daftar Media</h3>
            <div class="card-tools">
                <form method="GET" action="{{ route('admin.media.index') }}" class="d-flex" style="gap: 6px;">
                    <select name="type" class="form-control form-control-sm" style="width: 130px;">
                        <option value="all" @selected(request('type','all')=='all')>Semua</option>
                        <option value="image" @selected(request('type')=='image')>Gambar</option>
                        <option value="svg" @selected(request('type')=='svg')>SVG</option>
                    </select>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama..." class="form-control form-control-sm" style="width: 200px;">
                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                    @if(request('q') || request('type','all')!='all')
                        <a href="{{ route('admin.media.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card-body">
            @if($media->isEmpty())
                <p class="text-center text-muted py-4">Belum ada media. Upload gambar pertama.</p>
            @else
                <div class="row">
                    @foreach($media as $m)
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="d-flex align-items-center justify-content-center bg-light" style="height: 150px; overflow: hidden;">
                                    @if(str_starts_with($m->mime_type, 'image/'))
                                        <img src="{{ asset('storage/'.$m->file_path) }}" alt="{{ $m->alt_text }}" style="max-height: 150px; max-width: 100%; object-fit: contain;">
                                    @else
                                        <i class="fas fa-file fa-2x text-muted"></i>
                                    @endif
                                </div>
                                <div class="card-body p-2">
                                    <p class="small font-weight-bold mb-1 text-truncate" title="{{ $m->original_name }}">{{ $m->original_name }}</p>
                                    <small class="text-muted d-block">{{ $m->width ? $m->width.'×'.$m->height.' · ' : '' }}{{ number_format($m->size/1024, 1) }} KB</small>
                                    @if($m->thumbnail_path)
                                        <small class="text-success d-block"><i class="fas fa-check mr-1"></i>Thumb 300 & 800</small>
                                    @endif
                                    <div class="mt-2 d-flex flex-wrap" style="gap: 4px;">
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
                <div class="mt-3 d-flex justify-content-center">
                    {{ $media->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.custom-file-input').forEach(function(input){
            input.addEventListener('change', function(e){
                var name = e.target.files[0] ? e.target.files[0].name : 'Pilih file...';
                e.target.nextElementSibling.textContent = name;
            });
        });
        document.querySelectorAll('.btn-copy-url').forEach(function(btn){
            btn.addEventListener('click', function(){
                var url = btn.getAttribute('data-url');
                if(navigator.clipboard) navigator.clipboard.writeText(url).then(function(){ Swal.fire({icon:'success',title:'URL disalin!',timer:1200,showConfirmButton:false}); });
                else { var ta=document.createElement('textarea'); ta.value=url; document.body.appendChild(ta); ta.select(); document.execCommand('copy'); document.body.removeChild(ta); Swal.fire({icon:'success',title:'URL disalin!',timer:1200,showConfirmButton:false}); }
            });
        });
        document.querySelectorAll('.btn-pick-media').forEach(function(btn){
            btn.addEventListener('click', function(){
                if(window.opener && window.opener.mediaPickerCallback){
                    window.opener.mediaPickerCallback({path: btn.dataset.path, url: btn.dataset.url, alt: btn.dataset.alt});
                    window.close();
                }
            });
        });
    });
    </script>
    @endpush
</x-layouts.admin>
