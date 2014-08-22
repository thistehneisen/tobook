@extends ('layouts.default')

@section ('content')
<h1 class="comfortaa">{{ trans('co.all_consumers') }}</h1>

<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Joined</th>
            <th>Active services</th>
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
            <td><input type="checkbox" name="id[]" value="{{ $item->id }}"></td>
            <td>{{ $item->id }}</td>
            <td>{{ $item->first_name }}</td>
            <td>{{ $item->last_name }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->created_at->format(trans('common.date_format')) }}</td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
@stop
