@extends ('layouts.default')

@section('main-classes') intro container @stop

@section('content')
<div class="row-fluid">
    <img src="{{ asset_path('core/img/front/directories/1.jpg') }}" class="img-responsive banner" alt="" />
    <h2 class="text-center">Varaa.com leading bookable platform</h2>

    <p>Take your business to new heights, we’re here to help. We provide you an innovative tool to transform your business into commercialized way and open up new door for revenue opportunities.</p>

    <div class="highlight row" style="padding-bottom:0 !important">
        <div class="col-lg-9 col-sm-8">
            <h3>How we make it possible for you?</h3>
            <ul>
                <li>Advance your search engine by adding real-time service availability and details</li>
                <li>Empower your customers ability to book right from the directory listings</li>
                <li>Offer SME sector a powerful online booking platform to approach new customers from various channels and to encourage repeat businesses</li>
                <li>You get 15% commission from every booking done through your channel</li>
            </ul>
        </div>
        <div class="col-lg-3 col-sm-4">
            <img src="{{ asset_path('core/img/front/directories/2.png') }}" class="img-responsive" alt="" />
        </div>
    </div>

    <div class="text-center">
        <h3>Convert your yellow page listing into a revenue listing</h3>
        <p>Our leading product, varaa.com booking system, commercialises traditional Internet yellow page and local search media; it enriches their business with real-time service availability, price information, and the ability to book a service immediately. It also offers SMBs a set of intuitive in-built marketing tools to help them grow their businesses.</p>
        <h3>Attractive, isn't it?</h3>
        <a href="{{ route('front.contact') }}" class="btn btn-lg btn-orange">Contact us!</a>
    </div>
</div>
@stop
