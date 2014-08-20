@extends('layouts.loyalty')

@section('title')
    @parent :: {{ trans('common.dashboard') }}
@stop

@section('scripts')
    {{ HTML::script('assets/js/jquery.dataTables.js') }}
    {{ HTML::script('assets/js/DT_bootstrap.js') }}
    {{ HTML::script('assets/js/consumerList.js') }}
@stop

@section('sub-content')
    <div class="consumer-buttons">
        <button class="btn-u btn-u-blue" onclick="onAddConsumer()">Add</button>
        <button class="btn-u btn-u-red" onclick="onDeleteConsumer()">Delete</button>
    </div>
    <div class="clear-both"></div>
    <div class="panel orange margin-bottom-40">
        <div class="panel-heading orange">
            <h3 class="panel-title">
                <!--<i class="icon-user"></i>-->
                Consumer List
            </h3>
        </div>
        <table class="table table-striped" id="tblDataList">
            <thead>
                <tr>
                    <th style="width: 60px;"><input type="checkbox" id="checkAll" onclick="onCheckAll(this)" /></th>
                    <th style="width: 60px;">No</th>
                    <th>Consumer</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Last Visited</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consumers as $key => $value)
                <tr>
                    <td>
                        <input type="checkbox" id="chkConsumerId" value="{{ $value->id }}" />
                    </td>
                    <td>{{ $key }}</td>
                    <td>
                        <a href="consumerForm.php?id={{ $value->id }}">{{ $value->first_name }} {{ $value->last_name }}</a>
                    </td>
                    <td>
                        <a href="consumerForm.php?id={{ $value->id }}">{{ $value->email }}</a>
                    </td>
                    <td>
                        <a href="consumerForm.php?id={{ $value->id }}">{{ $value->phone }}</a>
                    </td>
                    <td>
                        <a href="consumerForm.php?id={{ $value->id }}">{{ $value->updated_at }}</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
