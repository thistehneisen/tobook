@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.home') }}
@stop

@section('scripts')

    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language='.App::getLocale()) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.plugin.min.js')) }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.countdown.min.js')) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    {{ HTML::script(asset_path('as/scripts/layout-3.js')) }}

    <script>
$(function() {
    new GMaps({
      div: '#map-canvas',
      lat: -12.043333,
      lng: -77.028333
    });
});
    </script>
@stop

@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
    <link rel="stylesheet" href="{{ asset_path('as/styles/layout-3.css') }}">
@stop

@section('main-classes') front @stop

@section('content')
<div class="container search-results">
    <a href="#">
        <h4 class="heading">
            <i class="fa fa-chevron-left"></i>
            <span class="keyword">Frizētava</span>,
            <span class="location">Centrs</span>,
            jebkurā dienā,
            jebkurā laikā:
            <span class="results">38 rezultāti</span>
        </h4>
    </a>

    <div class="row">
        {{-- left sidebar --}}
        <div class="col-sm-8 col-md-8">
            <h1>Seules Salons</h1>
            <address>Brīvības iela 18, Rīga</address>

            <div class="slideshow">
                <p><img src="{{ asset_path('core/img/new/business.jpg') }}" alt=""></p>
            </div>

            <h3 class="sub-heading">Par mums</h3>
            <div class="description">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit accusantium saepe, ipsum voluptas aliquam, unde totam officia distinctio recusandae voluptates ipsam, quam hic odit illo voluptatum praesentium quod minus tempore.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus repudiandae error dolores inventore distinctio dolorem in tempora autem amet pariatur! Ratione obcaecati suscipit expedita molestias asperiores laudantium, tenetur mollitia debitis!</p>
            </div>
        </div>

        {{-- right sidebar --}}
        <div class="col-sm-4 col-md-4">
            <h3 class="sub-heading">Map</h3>
            <div id="map-canvas" class="small-map"></div>

            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <h3 class="sub-heading">Openning hours</h3>
                    <table class="table table-working-hours">
                        <tbody>
                            <tr>
                                <td>Mon</td>
                                <td>10:00 - 19:00</td>
                            </tr>
                            <tr>
                                <td>Tue</td>
                                <td>10:00 - 19:00</td>
                            </tr>
                            <tr>
                                <td>Wed</td>
                                <td>10:00 - 19:00</td>
                            </tr>
                            <tr>
                                <td>Thu</td>
                                <td>10:00 - 19:00</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col-sm-6 col-md-6">
                    <h3 class="sub-heading">Contact</h3>
                    <p><strong>Administratore:</strong></p>
                    <p>123456 789</p>
                    <p>123456 789</p>

                    <p><strong>Mobilais:</strong></p>
                    <p>123 456 789</p>

                    <p><strong>E-mail</strong></p>
                    <p>abak@alnal.com</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
