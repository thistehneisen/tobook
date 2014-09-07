@extends ('layouts.business')

@section('title')
    @parent :: {{ trans('common.home') }}
@stop

@section('styles')
{{ HTML::style(asset('packages/jquery.bxslider/jquery.bxslider.css')) }}
@stop

@section('scripts')
{{ HTML::script(asset('packages/jquery.bxslider/jquery.bxslider.min.js')) }}
<script>
    $(function () {
        $('.bxslider').bxSlider({
            controls: false,
            auto: true,
            pager: false
        });

        $('header').addClass('homepage');
    });
</script>
@stop

@section('page-header')
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
<p class="text-header">{{ trans('home.tagline') }}</p>
<a href="{{ URL::route('auth.register') }}" class="btn btn-default btn-lg text-uppercase">{{ trans('home.start_now') }} <i class="fa fa-arrow-circle-right"></i></span></a>
@stop
