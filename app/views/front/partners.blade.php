@extends ('layouts.default')

@section('styles')
    {{ HTML::style('assets/css/intro.css') }}
@stop

@section('main-classes') intro container @stop

@section('content')
<div class="row-fluid">
    <img src="{{ asset('assets/img/front/partners/1.jpg') }}" class="img-responsive banner" alt="" />
    <h3>Parner with varaa.com and increase your sales by reselling our industry leading online booking systems</h3>
    <p>Varaa.com offers ready to go online booking systems for Restaurants, Salons, Beauty Centers and Sports Activities.<br>
    Whether you are a daily deal business, looking to gain visibility of your customers’ occupation rates or a service company offering added value to your client's, varaa.com has the tools you need.<br>
    Our online booking systems have the customization tools and features you require.</p>

    <h3>Your Leading Brand our trusted products</h3>
    <p>Partner with varaa.com and start reselling industry leading online booking systems to your existing customer base under your own brand name. <br>
    If you are in the Daily Deals business attain a strong mutually beneficial relationship with your clients, monitor customer occupation rates and make that all important sales call at the right time! <br>
    If your business exists to provide added value to your clients, our online booking systems will add another tool to help your customers’ win more business.</p>

    <h3>Stronger Together</h3>
    <p>A Partnership with varaa.com is based on mutual trust, a strong passion to achieve more and an iron determination to provide customers with the very best solutions for their business needs. We operate a best advice policy, we listen to our clients and only provide solutions we are sure will increase their sales and streamline their business operations. <br>
    Does this sound like a good fit with your business? Then we stand ready to talk to you. <br>
    <a href="{{ route('front.contact') }}">Contact us</a> today to find out more!</p>
</div>
@stop
