@extends ('layouts.default')

@section ('title')
    Home :: @parent
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
});
    </script>
@stop

@section ('content')
            <div class="row services">
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconHome.png') }}" alt=""></p>
                        <p>Kotisivut</p>
                    </a>
                </div>
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconLoyality.png') }}" alt=""></p>
                        <p>Kantiskortti</p>
                    </a>
                </div>
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconAppointment.png') }}" alt=""></p>
                        <p>Ajanvaraus</p>
                    </a>
                </div>
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconCustomer.png') }}" alt=""></p>
                        <p>Asiakasrekisteri</p>
                    </a>
                </div>
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconCashier.png') }}" alt=""></p>
                        <p>Kassa</p>
                    </a>
                </div>
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconMarketing.png') }}" alt=""></p>
                        <p>Markkinointityokalut</p>
                    </a>
                </div>
            </div>
@stop
