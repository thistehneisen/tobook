@extends ('modules.as.layout')

@section ('content')
<div class="alert alert-info">
    <p><strong>{{ trans('as.embed.heading') }}</strong></p>
    <p>{{ trans('as.embed.description') }}</p>
</div>

<div class="row">
    <div class="col-sm-12">
        <textarea class="form-control" cols="30" rows="10" style="font-family: monospace;"><iframe width="100%" height="1000px" src="{{ $link }}"></textarea>
    </div>
</div>
@stop
