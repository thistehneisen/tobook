<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('loyalty-card.consumer_list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ trans('loyalty-card.number') }}</th>
                <th>{{ trans('loyalty-card.consumer') }}</th>
                <th>{{ trans('co.email') }}</th>
                <th>{{ trans('co.phone') }}</th>
                <th>{{ trans('loyalty-card.last_visited') }}</th>
                <th>{{ trans('common.delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consumers as $key => $value)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>
                    <a href="{{ URL::route('lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->consumer->getNameAttribute() }}</a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->consumer->email }}</a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->consumer->phone }}</a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->consumer->updated_at }}</a>
                </td>
                <td>
                    @if ($app)
                    {{ Form::open(['url' => ['/api/v1.0/lc/consumers', $value->id], 'method' => 'delete']) }}
                    @else
                    {{ Form::open(['route' => ['lc.consumers.delete', $value->id], 'method' => 'delete']) }}
                    @endif
                    <div class="delete-Button" id="{{ $value->id }}">
                        <button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#js-confirmDeleteModal">
                            <span class="glyphicon glyphicon-trash"></span> {{ trans('common.delete') }}
                        </button>
                    </div>
                    {{ Form::close() }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $consumers->links() }}</div>
</div>
