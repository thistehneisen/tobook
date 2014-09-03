@extends('modules.mt.layout')
@section('styles')
    {{ HTML::style('assets/wysiwyg/bootstrap/bootstrap_extend.css') }}
    {{ HTML::style('assets/css/marketing.css') }}
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.campaign.list') }}</h3>
    </div>
    <div class="panel-body">
        {{ Form::model($campaign, array('route' => array('mt.campaigns.update', $campaign->id), 'method' => 'PUT', 'class' => 'form-horizontal')) }}
        @foreach ([
            'subject'          => trans('mt.campaign.subject'),
            'from_email'       => trans('mt.campaign.from_email'),
            'from_name'        => trans('mt.campaign.from_name'),
            'content'          => trans('common.content'),
            'created_at'       => trans('common.created_at'),
            'updated_at'       => trans('common.updated_at'),
        ] as $key => $value)
        <div class="form-group">
            <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
            <div class="col-md-8">
                    @if ($key === 'content')
                        {{ Form::textarea($key, null, ['class' => 'form-control']) }}
                    @elseif ($key === 'created_at' || $key === 'updated_at')
                        {{ Form::text($key, null, ['class' => 'form-control', 'disabled' => 'disabled']) }}
                    @else
                        {{ Form::text($key, null, ['class' => 'form-control']) }}
                        {{ $errors->first($key) }}
                    @endif
            </div>
            <div class="col-md-2">
                {{ $errors->first($key) }}
            </div>
        </div>
        @endforeach
        <div class="form-group">
            <div class="col-sm-offset-5 col-sm-10">
                {{ Form::button(trans('mt.template.list'), ['class' => 'btn btn-success', 'id' => 'btn-templates', ]) }}
                {{ Form::submit(trans('mt.campaign.edit'), ['class' => 'btn btn-primary', 'onclick' => 'onSetContent()', ]) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="templatesModal" tabindex="-1" role="dialog" aria-labelledby="templatesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="templatesModalLabel">{{ trans('mt.template.list') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row" id="template-list">
                    @foreach ($templates as $key => $value)
                        <div class="col-md-4 text-center" id="template-item">
                            <input id="template-id" type="hidden" value="{{ $value['id'] }}"/>
                            <p>
                                <img class="thumb-img" src="{{ $value['thumbnail'] }}"/>
                            </p>
                            <p>
                                {{ $key + 1 }}.&nbsp;{{ $value['content'] }}
                            </p>
                        </div>
                    @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.close') }}</button>
                    <button type="button" class="btn btn-primary" id="btn-select-template">{{ trans('common.select') }}</button>
                </div>
            </div>
        </div>
    </div>
        
</div>
@section('scripts')
    <script src="{{ asset('assets/js/mt/common.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/mt/campaigns.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/wysiwyg/scripts/innovaeditor.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/wysiwyg/scripts/innovamanager.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/wysiwyg/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script>
        $('#content').liveEdit({
        	fileBrowser: "{{ asset('assets/wysiwyg/assetmanager/asset.php?type=campaign&user_id='.Confide::user()->id) }}",
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
