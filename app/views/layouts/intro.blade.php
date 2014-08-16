@extends ('layouts.default')

@section('content')
<div class="row services">
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-website-list') }}">
            <p><img src="{{ asset('assets/img/iconHome.png') }}" alt=""></p>
            <p>{{ trans('home.homepages') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-loyalty') }}">
            <p><img src="{{ asset('assets/img/iconLoyality.png') }}" alt=""></p>
            <p>{{ trans('home.loyaltycard') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-timeslot') }}">
            <p><img src="{{ asset('assets/img/iconAppointment.png') }}" alt=""></p>
            <p>{{ trans('dashboard.timeslot') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-customer-registration') }}">
            <p><img src="{{ asset('assets/img/iconCustomer.png') }}" alt=""></p>
            <p>{{ trans('home.customer_registration') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-cashier') }}">
            <p><img src="{{ asset('assets/img/iconCashier.png') }}" alt=""></p>
            <p>{{ trans('home.cashier') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="{{ URL::route('intro-marketing-tools') }}">
            <p><img src="{{ asset('assets/img/iconMarketing.png') }}" alt=""></p>
            <p>{{ trans('dashboard.marketing') }}</p>
        </a>
    </div>
</div>
@yield('intro_content')
@stop
