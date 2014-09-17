@if ($value)
    <span class="label label-success">{{ trans('common.yes') }}</span>
@else
    <span class="label label-danger">{{ trans('common.no') }}</span>
@endif
