<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('loyalty-card.consumer_list') }}</h3>
    </div>
    <table class="table table-hover" id="consumer-table">
        <thead>
            <tr>
                <th class="no-display">{{ trans('loyalty-card.number') }}</th>
                <th>{{ trans('loyalty-card.consumer.index') }}</th>
                <th class="no-display">{{ trans('co.email') }}</th>
                <th class="no-display">{{ trans('co.phone') }}</th>
                <th>{{ trans('loyalty-card.last_visited') }}</th>
                <th class="no-display">{{{ trans('common.edit') }}}</th>
                <th class="no-display">{{ trans('common.delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $key => $value)
            <tr data-consumerid="{{{ $value->id }}}">
                <td class="no-display">{{ $value->id }}</td>
                <td>
                    {{ $value->consumer->first_name }} {{ $value->consumer->last_name }}
                </td>
                <td class="no-display">
                    {{ $value->consumer->email }}
                </td>
                <td class="no-display">
                    {{ $value->consumer->phone }}
                </td>
                <td>
                    {{ $value->updated_at }}
                </td>
                <td class="no-display">
                    <a href="{{ URL::route('consumers.upsert', ['id' => $value->id]) }}">
                        <button class="btn btn-sm btn-success" type="button">
                            <span class="glyphicon glyphicon-pencil"></span> {{ trans('common.edit') }}
                        </button>
                    </a>
                </td>
                <td class="no-display">
                    <a data-href="{{ URL::route('consumers.delete', ['id' => $value->id]) }}" data-toggle="modal" data-target="#js-confirmDeleteModal" href="#">
                        <button class="btn btn-sm btn-danger" type="button">
                            <span class="glyphicon glyphicon-trash"></span> {{ trans('common.delete') }}
                        </button>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $items->links() }}</div>
</div>
