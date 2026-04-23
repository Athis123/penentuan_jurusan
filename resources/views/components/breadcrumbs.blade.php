<div class="section-header d-flex justify-content-between align-items-center px-4">
    <h1 class="mb-0">{{ $title }}</h1>
    <div class="section-header-breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$loop->last)
                <div class="breadcrumb-item">
                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                </div>
            @else
                <div class="breadcrumb-item active">{{ $breadcrumb['label'] }}</div>
            @endif
        @endforeach
    </div>
</div>
