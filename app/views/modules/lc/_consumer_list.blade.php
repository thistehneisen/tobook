<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('loyalty-card.consumer_list') }}</h3>
    </div>
    <table class="table table-striped" id="consumers">
        <thead>
            <tr>
                <th>{{ trans('loyalty-card.number') }}</th>
                <th>{{ trans('loyalty-card.consumer') }}</th>
                <th>{{ trans('co.email') }}</th>
                <th>{{ trans('co.phone') }}</th>
                <th>{{ trans('loyalty-card.last_visited') }}</th>
                <th class="edit">{{{ trans('common.edit') }}}</th>
                <th>{{ trans('common.delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consumers as $key => $value)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>
                    {{ $value->consumer->getNameAttribute() }}
                </td>
                <td>
                    {{ $value->consumer->email }}
                </td>
                <td>
                    {{ $value->consumer->phone }}
                </td>
                <td>
                    {{ $value->consumer->updated_at }}
                </td>
                <td class="edit">
                    <a href="{{ URL::route('lc.consumers.edit', ['id' => $value->id]) }}">
                        <button class="btn btn-sm btn-success" type="button">
                            <span class="glyphicon glyphicon-pencil"></span> {{ trans('common.edit') }}
                        </button>
                    </a>
                </td>
                <td>
                    {{ Form::open(['route' => ['lc.consumers.delete', $value->id], 'method' => 'delete']) }}
                    <button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#js-confirmDeleteModal">
                        <span class="glyphicon glyphicon-trash"></span> {{ trans('common.delete') }}
                    </button>
                    {{ Form::close() }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $consumers->links() }}</div>
</div>
