@extends ('modules.fd.layout')

@section ('content')
    @include ('modules.fd.index.tabs')

<div class="tab-content">
    <div class="tab-pane active">
        {{ $content }}
    </div>
</div>
@stop
