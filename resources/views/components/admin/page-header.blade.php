@props([
    'title' => '',
    'description' => null,
    'buttonHref' => null,
    'buttonLabel' => null,
])

<div class="row mb-3">
    <div class="col-sm-6">
        <h1 class="m-0">{{ $title }}</h1>
        @if ($description)
            <p class="text-muted">{{ $description }}</p>
        @endif
    </div>
    <div class="col-sm-6">
        @if ($buttonHref && $buttonLabel)
            <a href="{{ $buttonHref }}" class="btn btn-primary float-right">
                <i class="fas fa-plus mr-1"></i> {{ $buttonLabel }}
            </a>
        @endif
    </div>
</div>
