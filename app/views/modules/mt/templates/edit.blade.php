@extends('modules.mt.layout')
@section('styles')
    {{ HTML::style('assets/wysiwyg/bootstrap/bootstrap_extend.css') }}
    {{ HTML::style('assets/css/marketing.css') }}
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.template.list') }}</h3>
    </div>
    <div class="panel-body">
        {{ Form::model($template, array('route' => array('mt.templates.update', $template->id), 'method' => 'PUT', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) }}
        @foreach ([
            'name'        => trans('common.name'),
            'thumbnail'   => trans('mt.template.thumbnail'),
            'content'     => trans('common.content'),
            'created_at'  => trans('common.created_at'),
            'updated_at'  => trans('common.updated_at'),
        ] as $key => $value)
            <div class="form-group">
                <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                <div class="col-md-8">
                @if ($key === 'content')
                    {{ Form::textarea($key, null, ['class' => 'form-control']) }}
                @elseif ($key === 'thumbnail')
                    {{ Form::file($key, null, ['class' => 'form-control']) }}                    
                @elseif ($key === 'created_at' || $key === 'updated_at')
                    {{ Form::text($key, null, ['class' => 'form-control', 'disabled' => 'disabled']) }}                             
                @else
                    {{ Form::text($key, null, ['class' => 'form-control']) }}
                @endif
                </div>
                <div class="col-md-2">
                    {{ $errors->first($key) }}
                </div>
            </div>
        @endforeach
        <div class="form-group">
            <div class="col-sm-offset-5 col-sm-10">
                {{ Form::submit(trans('mt.template.edit'), ['class' => 'btn btn-primary', 'onclick' => 'onSetContent()', ]) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
@section('scripts')
    <script src="{{ asset('assets/js/mt/common.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/wysiwyg/scripts/innovaeditor.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/wysiwyg/scripts/innovamanager.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/wysiwyg/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script>
        $('#content').liveEdit({
        	fileBrowser: "{{ asset('assets/wysiwyg/assetmanager/asset.php?type=template&user_id='.Confide::user()->id) }}",
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
