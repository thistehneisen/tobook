@extends ('layouts.default')

@section ('title')
    @parent :: {{ trans('common.home') }}
@stop

@section ('styles')
{{ HTML::style(asset('packages/jquery.bxslider/jquery.bxslider.css')) }}
@stop

@section ('scripts')
{{ HTML::script(asset('packages/jquery.bxslider/jquery.bxslider.min.js')) }}
<script>
    $(function() {
        $('.bxslider').bxSlider({
            controls: false,
            auto: true,
            pager: false
        });

        $('header').addClass('homepage');
    });
</script>
@stop

@section ('header')
<div class="imac-wrapper">
    <div class="imac">
        <ul class="bxslider">
          <li><img src="{{ asset('assets/img/slides/1.jpg') }}" /></li>
          <li><img src="{{ asset('assets/img/slides/2.jpg') }}" /></li>
          <li><img src="{{ asset('assets/img/slides/3.jpg') }}" /></li>
          <li><img src="{{ asset('assets/img/slides/4.jpg') }}" /></li>
      </ul>
  </div>
</div>
<p><img src="{{ asset('assets/img/homepage-text.png') }}" alt="" class="img-homepage"></p>
<p><a href="#"><img src="{{ asset('assets/img/btn-aloita-nyt.jpg') }}" alt=""></a></p>
@stop

@section ('content')
<div class="row services">
    <div class="col-md-2 col-lg-2">
        <a href="">
            <p><img src="{{ asset('assets/img/iconHome.png') }}" alt=""></p>
            <p>{{ trans('home.homepages') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="">
            <p><img src="{{ asset('assets/img/iconLoyality.png') }}" alt=""></p>
            <p>{{ trans('home.loyaltycard') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="">
            <p><img src="{{ asset('assets/img/iconAppointment.png') }}" alt=""></p>
            <p>{{ trans('dashboard.timeslot') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="">
            <p><img src="{{ asset('assets/img/iconCustomer.png') }}" alt=""></p>
            <p>{{ trans('home.customer_registration') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="">
            <p><img src="{{ asset('assets/img/iconCashier.png') }}" alt=""></p>
            <p>{{ trans('home.cashier') }}</p>
        </a>
    </div>
    <div class="col-md-2 col-lg-2">
        <a href="">
            <p><img src="{{ asset('assets/img/iconMarketing.png') }}" alt=""></p>
            <p>{{ trans('dashboard.marketing') }}</p>
        </a>
    </div>
</div>
@stop