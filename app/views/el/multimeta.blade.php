@if ($meta->isEmpty() === false)
    @section('meta')
        @foreach ($meta as $item)
            @if ($item->key === 'meta_title')
                @section('title')
                {{ $item->value }}
                @stop
            @else <meta name="{{ $item->key }}" content="{{ $item->value }}">
            @endif
        @endforeach
    @stop
@endif
