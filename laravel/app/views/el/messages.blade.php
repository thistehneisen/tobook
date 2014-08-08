    @if ($errors->isEmpty() === false)
        <div class="alert alert-danger">
            <p><strong>Errors!</strong></p>
        @foreach ($errors->all() as $message)
            <p>{{ $message }}</p>
        @endforeach
        </div>
    @endif
