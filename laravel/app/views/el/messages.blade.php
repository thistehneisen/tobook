@if ($errors->top->isEmpty() === false)
    <div class="alert alert-danger">
        <p><strong>{{ trans('common.Errors') }}!</strong></p>
    @foreach ($errors->top->all() as $message)
        <p>{{ $message }}</p>
    @endforeach
    </div>
@endif

@if (($messages = Session::get('messages')) && $messages->isEmpty() === false)
    @foreach ($messages->toArray() as $type => $message)
        <div class="alert alert-{{ $type }}">
            <p><strong>{{ $message['title'] or 'Message' }}</strong></p>
            @foreach ($message['content'] as $msg)
            <p>{{ $msg }}</p>
            @endforeach
        </div>
    @endforeach
@endif
