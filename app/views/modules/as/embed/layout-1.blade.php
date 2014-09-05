@extends ('modules.as.embed.embed')

@section ('content')
<div class="container-fluid">
    <!-- Sidebar -->
    <div class="col-sm-3">
        <div class="panel panel-default">
            <div class="panel-heading">Select a date</div>
            <div class="panel-body">
                <div id="datepicker"></div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Selected services</div>
            <div class="panel-body">
                <div class="alert alert-info">Cart is empty</div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">Select a service</div>
            <div class="panel-body">
                @if(empty($employees))
                @include('modules.as.embed.service-1')
                @else
                @include('modules.as.embed.employee-1')
                @endif
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="get_extra_service_form" value="{{ route('as.embed.extra.form') }}">
@stop
