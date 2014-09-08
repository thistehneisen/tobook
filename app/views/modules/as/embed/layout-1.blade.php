@extends ('modules.as.embed.embed')

@section ('content')
<div class="container-fluid">
    <!-- Sidebar -->
    <div class="col-sm-3">
         @if($action !== 'checkout')
        <div class="panel panel-default">
            <div class="panel-heading">{{ trans('as.embed.select_date') }}</div>
            <div class="panel-body">
                <div id="datepicker"></div>
            </div>
        </div>
        @endif

        <div class="panel panel-default">
            <div class="panel-heading">{{ trans('as.embed.select_service') }}</div>
            <div class="panel-body">
            @if(empty(Session::get('carts')))
                <div class="alert alert-info">{{ trans('as.embed.empty_cart') }}</div>
                @else
                <?php $carts = Session::get('carts');?>
                @foreach($carts as $key => $item)
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $item['service_name'] }}</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6"> {{ $item['datetime'] }}</div>
                            <div class="col-sm-4"> {{ $item['price'] }} &euro;</div>
                            <div class="col-sm-2"> <a href="#" data-hash="{{ $hash }}" data-action-url="{{ route('as.bookings.service.remove.in.cart') }}" data-uuid="{{ $key }}" class="btn-remove-item-from-cart"><i class="glyphicon glyphicon-remove btn-danger"></i></a></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12"> {{ $item['start_at'] }} : {{ $item['end_at'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach

                <p class="text-right">
                <a href="{{ route('as.embed.embed', ['hash' => $hash, 'action' => 'checkout'])}}" class="btn btn-primary"><i class="glyphicon glyphicon-shopping-cart"></i> {{ trans('as.embed.checkout') }}</a>
                </p>
            @endif
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                @if($action === 'checkout')
                    {{ trans('as.embed.booking_form') }}
                @else
                    @if(empty($service))
                    {{ trans('as.embed.select_service') }} {{ $date->format('jS F') }}
                    @else
                    {{ $service->name }} {{ $date->format('jS F') }} <a href="{{ route('as.embed.embed', [ 'hash' => $hash ])}}">({{ trans('as.embed.back_to_services') }})</a>
                    @endif
                @endif
            </div>
            <div class="panel-body">
                @if($action === 'checkout')
                    @include('modules.as.embed.checkout-1')
                @else
                    @if(empty($employees))
                        @include('modules.as.embed.service-1')
                    @else
                        @include('modules.as.embed.employee-1')
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="extra-service-url" value="{{ route('as.embed.extra.form') }}">
<input type="hidden" name="date" id="txt-date" value="{{ Carbon\Carbon::now()->toDateString() }}">
@stop
