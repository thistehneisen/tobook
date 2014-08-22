@extends('modules.marketing.layout')
@section('styles')
    {{ HTML::style('assets/wysiwyg/bootstrap/bootstrap_extend.css') }}
@stop

@section('sub-content')
<nav class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ URL::route('modules.mt.campaigns.index') }}">Campaign Alert</a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::route('modules.mt.campaigns.index') }}">View All Campaigns</a></li>
        <li><a href="{{ URL::route('modules.mt.campaigns.create') }}">Create a Campaign</a></li>
    </ul>
</nav>

<h1>Create a campaign</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => URL::route('modules.mt.campaigns.store'))) }}
    @foreach ([
        'subject'    => trans('Subject'),
        'from_email' => trans('From Email'),
        'from_name'  => trans('From Name'),
    ] as $key => $value)
    <div class="form-group">
        {{ Form::label($key, $value) }}
        {{ Form::text($key, Input::old($key), array('class' => 'form-control')) }}
    </div>
    @endforeach
    <div class="form-group">
        {{ Form::label('content', trans('Content')) }}
        {{ Form::textarea('content', Input::old('content'), array('class' => 'form-control')) }}
    </div>
    {{ Form::submit(trans('Create the campaign!'), array('class' => 'btn btn-primary')) }}
{{ Form::close() }}

</div>
@section('scripts')
    <script src="{{ asset('assets/wysiwyg/scripts/innovaeditor.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/wysiwyg/scripts/innovamanager.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/wysiwyg/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script>
        $('#content').liveEdit({
        	fileBrowser: "{{ asset('assets/wysiwyg/assetmanager/asset.php?type=template') }}",
            height: 550,
            groups: [
                    ["group1", "", ["Bold", "Italic", "Underline", "ForeColor", "RemoveFormat"]],
                    ["group2", "", ["Bullets", "Numbering", "Indent", "Outdent"]],
                    ["group3", "", ["Paragraph", "FontSize"]],
                    ["group4", "", ["LinkDialog", "ImageDialog", "TableDialog", "SourceDialog"]],
                    ]
        });
        $('#content').data('liveEdit').startedit();
    </script>
@stop
@stop
