@if ($meta->isEmpty() === false)
    @section('meta')
        @foreach ($meta as $item) <meta name="{{ $item->key }}" content="{{ $item->value }}"> @endforeach
    @stop
@endif
