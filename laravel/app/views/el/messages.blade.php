@if ($errors->top->isEmpty() === false)
    <div class="alert alert-danger">
        <p><strong>Errors!</strong></p>
    @foreach ($errors->top->all() as $message)
        <p>{{ $message }}</p>
    @endforeach
    </div>
@endif


@if (isset($messages) && $messages->isEmpty() === false)
    @foreach ($messages->toArray() as $type => $message)
        <div class="alert alert-{{ $type }}">
            <p><strong>{{ $message['title'] or 'Message' }}</strong></p>
            <p>{{ $message['content'] }}</p>
        </div>
    @endforeach
@endif
