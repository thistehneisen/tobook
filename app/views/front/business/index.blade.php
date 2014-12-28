@extends ('layouts.default')

@section('main-classes') intro container @stop

@section('content')
<div class="row-fluid">
    <div class="text-center">
        <img src="{{ asset_path('core/img/front/business/1.jpg') }}" class="img-responsive banner" alt="" />
        <h2>Varaa.com, complete online booking solution</h2>
    </div>
    <p>Our free online booking system is perfect for restaurants, hair salons, beauty salons, car services, massages, healthcare services and more.</p>

    <div class="highlight clearfix">
        <div class="col-lg-9 col-sm-8">
            <h3>Our solution offers</h3>
            <ul>
                <li>Booking maximization</li>
                <li>Time and human resource minimization</li>
                <li>Marketing cost elimination </li>
                <li>User-friendly interface</li>
                <li>Branded customization</li>
                <li>No commission on all bookings from your website</li>
            </ul>
        </div>
        <div class="col-lg-3 col-sm-4">
            <img src="{{ asset_path('core/img/front/resellers/2.png') }}" class="img-responsive" alt="" />
        </div>
    </div>

    <img src="{{ asset_path('core/img/front/business/3.jpg') }}" class="img-responsive" alt="" />

    <div class="clearfix">
        <div class="col-lg-3 col-sm-4">
            <img src="{{ asset_path('core/img/front/business/4.jpg') }}" class="img-responsive" alt="" />
        </div>
        <p class="col-lg-9 col-sm-8" style="margin-top: 40px;">
            Our user interface is entirely customizable to show the feel and look of your own brand
        </p>
    </div>

    <img src="{{ asset_path('core/img/front/business/5.jpg') }}" class="img-responsive" alt="" />

    <div class="text-center">
        <h3>Attractive, isn't it?</h3>
        <a href="{{ route('front.contact') }}" class="btn btn-lg btn-orange">Contact us!</a>
    </div>
</div>
@stop
