@extends('modules.mt.layout')
@section('styles')
    {{ HTML::style('assets/wysiwyg/bootstrap/bootstrap_extend.css') }}
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.campaign.list') }}</h3>
    </div>
    <table class="table table-striped">
        <tbody>
            {{ Form::open(['route' => 'modules.mt.campaigns.store']) }}
            @foreach ([
                'subject'          => trans('mt.campaign.subject'),
                'from_email'       => trans('mt.campaign.from_email'),
                'from_name'        => trans('mt.campaign.from_name'),
                'content'          => trans('mt.campaign.content'),
            ] as $key => $value)
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label($key, $value) }}
                        @if (strcmp($key, 'content') === 0)
                            {{ Form::textarea($key, null, ['class' => 'form-control']) }}
                        @else
                            {{ Form::text($key, null, ['class' => 'form-control']) }}
                            {{ $errors->first($key) }}
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
            <tr>
                <td>
                {{ Form::submit(trans('mt.campaign.create'), ['class' => 'btn btn-primary', 'onclick' => 'onSetContent()', ]) }}
                </td>
            </tr>
            {{ Form::close() }}
        </tbody>
    </table>
</div>
@section('scripts')
    <script src="{{ asset('assets/js/mt/campaigns.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/wysiwyg/scripts/innovaeditor.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/wysiwyg/scripts/innovamanager.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/wysiwyg/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script>
        $('#content').liveEdit({
        	fileBrowser: "{{ asset('assets/wysiwyg/assetmanager/asset.php?type=campaign') }}",
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
