@extends ('modules.as.layout')

@section ('footer')
@stop

@section ('content')
<h3>This is how your booking form looks like</h3>
<iframe src="{{ $link }}" frameborder="0" style="width: 100%; height: auto;"></iframe>
@stop
