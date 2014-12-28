@extends ('layouts.default')

@section('main-classes') container-fluid intro @stop

@section('content')
<div class="row services">
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-website-list') }}">
            <p><img src="{{ asset_path('core/img/iconHome.png') }}" alt=""></p>
            <p>{{ trans('home.homepages') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-loyalty') }}">
            <p><img src="{{ asset_path('core/img/iconLoyality.png') }}" alt=""></p>
            <p>{{ trans('home.loyaltycard') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-online-booking') }}">
            <p><img src="{{ asset_path('core/img/iconAppointment.png') }}" alt=""></p>
            <p>{{ trans('intro.online_booking') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-customer-registration') }}">
            <p><img src="{{ asset_path('core/img/iconCustomer.png') }}" alt=""></p>
            <p>{{ trans('home.customer_register') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-cashier') }}">
            <p><img src="{{ asset_path('core/img/iconCashier.png') }}" alt=""></p>
            <p>{{ trans('home.cashier') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-marketing-tools') }}">
            <p><img src="{{ asset_path('core/img/iconMarketing.png') }}" alt=""></p>
            <p>{{ trans('dashboard.marketing') }}</p>
        </a>
    </div>
</div>
<div class="row guide-body">
    @yield('intro_content')
</div>
@stop
