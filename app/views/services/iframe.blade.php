@extends ('layouts.dashboard')

@section('title')
    {{ trans('common.dashboard') }}
@stop

{{-- Don't show the footer --}}
@section('footer')
@stop

{{-- Also no content --}}
@section('content')
@stop

{{-- And no logo --}}
@section('logo')
@stop

@section('scripts')
<script>
$(function () {
    // Remove main element
    $('main') .remove();
    // There should be a CSS solution for this
    $('iframe').height($(document).height() - $('header').outerHeight() - 5);
});
</script>
@stop

@section('iframe')
<iframe id="varaa-iframe" width="100%" src="{{ $url }}" frameborder="0"></iframe>
@stop
