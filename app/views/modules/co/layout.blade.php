@extends ('layouts.dashboard')

@section ('styles')
    @parent
    {{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
    {{ HTML::style(asset('packages/alertify/css/themes/default.min.css')) }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script(asset('assets/js/modules/co.js')) }}
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
@stop

@section('extra_modals')
<!-- History Modal Dialog -->
<div class="modal fade" id="js-historyModal" role="dialog" aria-labelledby="js-historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{ trans('History') }}</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('OK') }}</button>
            </div>
        </div>
    </div>
</div>
@stop
