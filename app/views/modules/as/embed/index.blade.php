@extends ('modules.as.layout')

@section ('content')
<div class="alert alert-info">
    <p><strong>{{ trans('as.embed.heading') }}</strong></p>
    <p>{{ trans('as.embed.description') }}</p>
</div>

<div class="row">
    @foreach ($links as $lang => $link)
    <div class="col-sm-12">
        <h3>{{ trans("as.embed.{$lang}_version") }}</h3>
        <textarea class="form-control" cols="30" rows="2" style="font-family: monospace;"><iframe width="100%" height="1000px" src="{{ $link }}"></iframe></textarea>
    </div>
    @endforeach
</div>
@stop
