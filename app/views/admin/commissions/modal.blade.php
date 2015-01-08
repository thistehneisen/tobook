<div class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('admin.commissions.index') }}</h4>
            </div>
        {{ Form::open() }}
            <div class="modal-body">
                <div class="form-group">
                    <label for="amount">{{ trans('admin.commissions.amount') }}</label>
                    {{ Form::text('amount', Input::get('amount'), ['class' => 'form-control', 'required']) }}
                </div>

                <div class="form-group">
                    <label for="note">{{ trans('admin.commissions.note') }}</label>
                    {{ Form::text('note', Input::get('note'), ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
            </div>
        {{ Form::close() }}
        </div>
    </div>
</div>
