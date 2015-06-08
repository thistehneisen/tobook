@extends ('layouts.admin')

@section('content')

<div id="form-olut-upsert" class="modal-form">
    @include ('el.messages')

    @if (isset($item->id))
        <h4 class="comfortaa">{{ trans($langPrefix.'.edit') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans($langPrefix.'.add') }}</h4>
    @endif
    <div role="tabpanel">
        <!-- Nav tabs -->
        {{ Form::open(['route' => ['admin.keywords.upsert', isset($item->id) ? $item->id : ''], 'class' => 'form-horizontal well', 'role' => 'form']) }}

            <div class="form-group">
                <label for="keyword" class="col-sm-2 control-label">{{ trans('admin.keywords.keyword') }}</label>
                <div class="col-sm-5">
                   {{ Form::text('keyword', !empty($item->keyword) ? ($item->keyword) : '', ['class' => 'form-control input-sm']) }}
                </div>
            </div>
            <div class="form-group">
                <label for="keyword" class="col-sm-2 control-label">{{ trans('admin.keywords.mapped') }}</label>
                <div class="col-sm-5">
                   {{ Form::select('mapped_id', [trans('common.options_select')]+$mappedObjects, isset($item->selected) ? $item->selected : '', ['class' => 'form-control input-sm', 'id' => 'mapped_id']) }}
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-5">
                    <button type="submit" class="btn btn-primary" id="btn-save-service">{{ trans('common.save') }}</button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
</div>
@stop

@section ('bottom_script')
<script>
$(function () {
    $('#language-tabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    });
});
</script>
@stop
