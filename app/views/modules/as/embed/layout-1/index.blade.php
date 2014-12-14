@extends ('modules.as.embed.embed')

@section ('extra_css')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css">
{{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
{{ HTML::style(asset('packages/alertify/css/themes/bootstrap.min.css')) }}

@include('modules.as.embed.layout-1._style')
@stop

@section ('extra_js')
<script src="//cdnjs.cloudflare.com/ajax/libs/purl/2.3.1/purl.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
{{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
<script>
$(document).ready(function () {
    // work around to fix problem clicking 3 times in 1 date: https://github.com/eternicode/bootstrap-datepicker/issues/1083
    Date.prototype.yyyymmdd = function () {
        var yyyy = this.getFullYear().toString();
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
        var dd  = this.getDate().toString();

        return yyyy + '-' + (mm[1] ? mm : '0' + mm[0]) + '-' + (dd[1] ? dd : '0' + dd[0]);
    };
    var date_param = purl(window.location.href).param('date'),
        selected_date = date_param ? date_param : (new Date()).yyyymmdd();
    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: new Date(),
        todayBtn: true,
        todayHighlight: true,
        weekStart: 1,
        language: '{{ App::getLocale() }}'
    }).on('changeDate', function (e) {
        if (e.format() !== '') {
            selected_date = e.format();
        }
        if (window.location.href.indexOf('date') !== -1) {
            window.location.href = window.location.href.replace(date_param, selected_date);
        } else {
            window.location.href = window.location.href + '?date=' + selected_date;
        }
    });
    $('#datepicker').datepicker('update', new Date({{ $date->year }},{{ $date->month - 1 }}, {{ $date->day }}));
    $('#txt-date').val('{{ $date->toDateString() }}');
    var slots = (parseInt($('#booking_length').val(), 10) / 15) - 1;
    var beforeSlots = (parseInt($('#booking_before').val(), 10) / 15);
    var totalSlots = (parseInt($('#booking_length').val(), 10) / 15) - 1; //subtract it self
    $('li.slot').each(function () {
        var len = $(this).nextAll('.active').length;
        var plustime = (parseInt($(this).data('plustime'), 10) / 15);

        if (len < slots) {
            $(this).removeClass('active');
            $(this).addClass('inactive');
        }
        var lenBefore = $(this).prevUntil('li.booked').length;
        if (lenBefore < beforeSlots) {
            $(this).removeClass('active');
            $(this).addClass('inactive');
        }
        var lenUntilBooked   = $(this).nextUntil('li.booked').length;
        var lenUntilFreetime = $(this).nextUntil('li.freetime').length;
        if(lenUntilBooked < (totalSlots + plustime) || lenUntilFreetime < (totalSlots + plustime)){
            $(this).removeClass('active');
            $(this).addClass('inactive');
        }
    });
});
</script>
@stop

@section ('content')
@if(!empty($user->asOptions['style_logo']) || (!empty($user->asOptions['style_banner'])))
<header class="container-fluid">
    <div class="row">
        @if(!empty($user->asOptions['style_logo']))
            <div class="logo"><img class="img-responsive" src="{{ $user->asOptions['style_logo'] }}" alt=""></div>
        @endif
        @if(!empty($user->asOptions['style_banner']))
            <div class="banner"><img class="img-responsive" src="{{ $user->asOptions['style_banner'] }}" alt=""></div>
        @endif
    </div>
</header>
@endif
<div class="container-fluid">
    <!-- Sidebar -->
    <div class="col-lg-3 col-md-4 col-sm-4">
         @if(empty($action))
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
            @if(empty($cart))
                <div class="alert alert-info">{{ trans('as.embed.empty_cart') }}</div>
                @else
                @foreach($cart->details as $item)
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $item->model->service_name }}</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">{{ $item->model->datetime }}</div>
                            <div class="col-sm-4">
                                @if ((bool)$user->asOptions['hide_prices'] === false)
                                {{ $item->price }} &euro;
                                @endif
                            </div>
                            <div class="col-sm-2"><a href="#" data-hash="{{ $hash }}" data-action-url="{{ route('as.bookings.service.remove.in.cart') }}" data-cart-id="{{ $cart->id }}" data-cart-detail-id="{{ $item->id }}" data-uuid="{{ $item->model->uuid }}" class="btn-remove-item-from-cart"><i class="glyphicon glyphicon-remove btn-danger"></i></a></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12"> {{ $item->model->instance->plainStartTime->format('h:i') }} : {{ $item->model->instance->plainEndTime->format('h:i') }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="col-lg-9 col-md-8 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                @if($action === 'checkout')
                    {{ trans('as.embed.booking_form') }}
                @elseif($action === 'confirm')
                    {{ trans('as.embed.booking_form') }}
                @else
                    @if(empty($service))
                    {{ trans('as.embed.select_service') }} {{ $date->format('d/m/Y') }}
                    @else
                    {{ $service->name }} {{ $date->format('d/m/Y') }} <a href="{{ route('as.embed.embed', [ 'hash' => $hash ])}}">({{ trans('as.embed.back_to_services') }})</a>
                    @endif
                @endif
            </div>
            <div class="panel-body">
                @if($action === 'checkout')
                    @include('modules.as.embed.layout-1.checkout-1')
                @elseif($action === 'confirm')
                    @include('modules.as.embed.layout-1.confirm-1')
                @else
                    @if(empty($employees))
                        @include('modules.as.embed.layout-1.service-1')
                    @else
                        @include('modules.as.embed.layout-1.employee-1')
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="extra-service-url" value="{{ route('as.embed.extra.form') }}">
<input type="hidden" name="date" id="txt-date" value="{{ Carbon\Carbon::now()->toDateString() }}">
@stop
