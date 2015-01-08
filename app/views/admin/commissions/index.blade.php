<div class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('admin.commissions.index') }}</h4>
            </div>
            <div class="modal-body">
                <table class="table table-hovered table-stripped">
                    <thead>
                        <tr>
                            <th>{{ trans('admin.commissions.amount') }}</th>
                            <th>{{ trans('admin.commissions.note') }}</th>
                            <th>{{ trans('admin.commissions.date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($commissions as $item)
                        <tr>
                            <td>{{ $item->signed_amount }}</td>
                            <td>{{ $item->note }}</td>
                            <td>{{ $item->created_at->format(trans('common.format.date_time')) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
