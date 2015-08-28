<table class="table table-hover">
    <thead>
        <tr>
            <th>{{ trans('co.date') }}</th>
            <th>{{ trans('co.employee') }}</th>
            <th>{{ trans('co.start_at') }}</th>
            <th>{{ trans('co.services') }}</th>
            <th>{{ trans('co.notes') }}</th>
            <th>{{ trans('common.created_at') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($history as $value)
        <tr>
            <td>{{ $value->date }}</td>
            <td>{{ $value->employee_name }}</td>
            <td>{{ $value->start_at }}</td>
            <td>{{ $value->name }}</td>
            <td>{{ $value->notes }}</td>
            <td>{{ $value->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td class="history" colspan="7">{{ $history->links('modules.as.bookings.partial.ajaxPaginator') }}</td>
        </tr>
    </tfoot>
</table>
<script>
$(function () {
    $(document).on('click', 'a.ajaxPaginatorLink', function(e){
        e.preventDefault();
        var $this = $(this);
        $.ajax({
            type: 'GET',
            url: $this.prop('href'),
            dataType: 'html'
        }).done(function (data) {
            $('#js-historyModal').find('.modal-body').html(data);
        });
        return false;
    });
});
</script>
