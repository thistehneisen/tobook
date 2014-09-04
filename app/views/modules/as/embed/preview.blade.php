@extends ('modules.as.layout')

{{-- Don't show the footer --}}
@section ('footer')
@stop

{{-- Also no content --}}
@section ('content')
@stop

@section ('scripts')
<script>
$(function () {
    // Remove main element
    $('main') .remove();
    // There should be a CSS solution for this
    $('iframe').height($(document).height() - $('header').outerHeight() - 5);
});
</script>
@stop

@section ('iframe')
<iframe id="varaa-iframe" width="100%" src="{{ $link }}" frameborder="0"></iframe>
@stop
