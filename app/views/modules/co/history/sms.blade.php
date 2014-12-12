@extends ('modules.co.layout')

@section ('content')
    @include ('el.messages')

{{ Form::open(['route' => 'consumer-hub.history.sms', 'class' => 'form-inline form-table', 'id' => 'form-bulk']) }}
<table class="table table-hover table-crud">
    <thead>
        <tr>
            <th><input type="checkbox" class="toggle-check-all-boxes" data-checkbox-class="checkbox"></th>
            <th>{{ trans('co.sms.title') }}</th>
            <th>{{ trans('co.groups.name') }}</th>
            <th>{{ trans('co.phone') }}</th>
            <th>{{ trans('co.sms.sent_at') }}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody id="js-crud-tbody">
    @foreach ($histories as $history)
        <tr id="row-{{ $history->id }}" data-id="{{ $history->id }}" class="item-row">
            <td><input type="checkbox" class="checkbox" name="ids[]" value="{{ $history->id }}" id="bulk-item-{{ $history->id }}"></td>
            <td>
                <a href="{{ route('consumer-hub.sms.upsert', [$history->sms->id]) }}" target="_blank">
                    {{ $history->sms->title }}
                </a>
            </td>
            <td>
            @if ($history->group)
                <a href="{{ route('consumer-hub.groups.upsert', [$history->group->id]) }}" target="_blank">
                    {{ $history->group->name }}
                </a>
            @endif
            </td>
            <td>
            @if ($history->consumer)
                <a href="{{ route('consumer-hub.upsert', [$history->consumer->id]) }}" target="_blank">
                    {{ $history->consumer->phone }}
                </a>
                ({{ $history->consumer->name }})
            @endif
            </td>
            <td>{{ $history->created_at }}</td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-6 text-right">
        {{  $histories->links() }}
    </div>

    <div class="col-md-2 text-right">
        <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
            {{ trans('olut::olut.per_page') }} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="{{ route('consumer-hub.history.sms', ['perPage' => 5]) }}" id="per-page-5">5</a></li>
                <li><a href="{{ route('consumer-hub.history.sms', ['perPage' => 10]) }}" id="per-page-10">10</a></li>
                <li><a href="{{ route('consumer-hub.history.sms', ['perPage' => 20]) }}" id="per-page-20">20</a></li>
                <li><a href="{{ route('consumer-hub.history.sms', ['perPage' => 50]) }}" id="per-page-50">50</a></li>
            </ul>
        </div>
    </div>
</div>
{{ Form::close() }}
@stop
