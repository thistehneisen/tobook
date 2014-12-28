@extends ('layouts.default')

@section('main-classes') intro container @stop

@section('content')
<div class="row-fluid">
    <img src="{{ asset('assets/img/front/media/1.jpg') }}" class="img-responsive banner" alt="" />
    <h2 class="text-center">Varaa.com Media Solutions</h2>

    <p>Stay ahead in the e-commerce era, we are here to help you achieve your targets. Our expertise team will build for you a tailored solution that leverages your revenue and attract customers.</p>

    <div class="highlight row">
        <div class="col-lg-9 col-sm-8">
            <h3>A powerful solution enables you</h3>
            <ul>
                <li>Approach huge inventory of local targeted SME businesses</li>
                <li>Get access to customers’ occupation rates</li>
                <li>Receive automatically updated content ads for your customers</li>
                <li>Fill up free online marketing space with banners, flash deals and available timeslots to drive revenue</li>
                <li>Enjoy our seamless sales system and focus on your main business</li>
                <li>Choose businesses and deals next to your locations easily through our constantly updated widget</li>
                <li>Invest no time and money while make sense of your free online marketing space</li>
                <li>Get 15% commission from every booking generated</li>
            </ul>
        </div>
        <div class="col-lg-3 col-sm-4">
            <img src="{{ asset('assets/img/front/media/2.png') }}" class="img-responsive" alt="" />
        </div>
    </div>
</div>
@stop
