@extends ('layouts.default')

@section('styles')
    {{ HTML::style('assets/css/intro.css') }}
@stop

@section('main-classes') intro container @stop

@section('content')
<div class="row-fluid">
    <img src="{{ asset('assets/img/front/resellers/1.jpg') }}" class="img-responsive" alt="" />
    <h2 class="text-center">Varaa.com Reseller Network</h2>

    <p>Do you want have trouble to cover living expenses? Does your current job not allow you to grow or earn more? Do you want to create your own job security? Join our reseller network and blow all your worries away.</p>
    <p>Varaa.com offer a FREMIUM all-in-one business solution for SME businesses to get exposure in the media channels.</p>

    <div class="highlight clearfix">
        <div class="col-lg-9 col-sm-8">
            <h3>What we offer you? More than an easy way to raise income</h3>
            <ul>
                <li>Our solution is very easy to sell. Actually you do not need to sell it, just introduce it</li>
                <li>Sign up to be a Varaa.com reseller and start signing up contracts with any SME businesses in the service industry</li>
                <li>Fill up your free time to meet local SME businesses and offer our FREEMIUM solution for them</li>
                <li>Once businesses sign up the contracts, they will become visible in all Varaa.com affiliate network</li>
                <li>For each booking or flash deal sold through the affiliate network, you gain 7.5% commission</li>
                <li>Start building your own team once you reach "platinium level"</li>
            </ul>
        </div>
        <div class="col-lg-3 col-sm-4">
            <img src="{{ asset('assets/img/front/resellers/2.png') }}" class="img-responsive" alt="" />
        </div>
    </div>

    <div class="text-center">
        <h3>What we require from you?</h3>
        <p>Nothing else but great personality and a right attitude to learn. We’ll provide you all necessary tools and knowledge to make your income fly.</p>
        <h3>Attractive, isn't it?</h3>
        <a href="{{ route('front.contact') }}" class="btn btn-lg btn-orange">Contact us!</a>
    </div>
</div>
@stop
