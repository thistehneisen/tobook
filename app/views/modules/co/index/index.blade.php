@extends ('layouts.default')

@section ('scripts')
<script>
$(function() {
    $('input.check-all').on('click', function() {
        var $this = $(this);
        $('input.check-all-child').prop('checked', $this.prop('checked'));
    });
});
</script>
@stop

@section ('content')
<h1 class="comfortaa">{{ trans('co.all_consumers') }}</h1>

{{ Form::open(['route' => 'co.bulk', 'class' => 'form-inline', 'role' => 'form']) }}
@include ('el.messages')
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th><input type="checkbox" class="check-all"></th>
            <th>{{ trans('co.id') }}</th>
            <th>{{ trans('co.first_name') }}</th>
            <th>{{ trans('co.last_name') }}</th>
            <th>{{ trans('co.email') }}</th>
            <th>{{ trans('co.joined') }}</th>
            <th>{{ trans('co.active_services') }}</th>
        </tr>
    </thead>
    <tbody>
    @if ($consumers->isEmpty())
        <tr>
            <td colspan="8" class="text-info text-center">{{ trans('co.empty') }}</td>
        </tr>
    @endif
    @foreach ($consumers as $item)
        <tr>
            <td><input type="checkbox" class="check-all-child" name="id[]" value="{{ $item->id }}"></td>
            <td><a href="{{ route('co.edit', ['id' => $item->id]) }}">{{ $item->id }} <i class="fa fa-edit"></i></a></td>
            <td>{{ $item->first_name }}</td>
            <td>{{ $item->last_name }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->created_at->format(trans('common.format.date_time')) }}</td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="action">{{ trans('co.with_selected') }}:</label>
            <select name="action" id="action" class="form-control input-sm">
                <option value="hide">{{ trans('co.delete') }}</option>
            </select>
        </div>

        <div class="form-group">
            <button class="btn btn-primary btn-sm">{{ trans('common.save') }}</button>
        </div>
    </div>
    <div class="col-md-6">
        <div class="pagination">
            {{ $consumers->links() }}
        </div>
    </div>
</div>

{{ Form::close() }}

@stop
