@extends ('layouts.intro')

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
