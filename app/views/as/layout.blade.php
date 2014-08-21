@extends ('layouts.default')

@section ('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
@stop

@section ('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script>
$(function() {
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd'
    });
});
    </script>
@stop

@section ('content')
    <ul class="nav nav-pills nav-" role="tablist">
        <li class="active"><a href="{{ route('as.index') }}"><i class="fa fa-calendar"></i> Kalenteri</a></li>
        <li><a href="#"><i class="fa fa-bookmark"></i> Varaukset</a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                <i class="fa fa-cloud"></i> Palvelut <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('as.services.index') }}">Index</a></li>
                <li><a href="{{ route('as.services.create') }}">Lisää palveluita</a></li>
                <li><a href="{{ route('as.services.categories') }}">Lisää kategoria</a></li>
                <li><a href="">Lisää resurssi</a></li>
                <li><a href="">Lisää lisäpalvelu</a></li>
            </ul>
        </li>
        <li><a href="#"><i class="fa fa-users"></i> Työntekijät</a></li>
        <li><a href="#"><i class="fa fa-cog"></i> Asetukset</a></li>
        <li><a href="#"><i class="fa fa-signal"></i> Raportit</a></li>
        <li><a href="#"><i class="fa fa-arrow-down"></i> Asenna</a></li>
        <li><a href="#"><i class="fa fa-desktop"></i> Esikatselu</a></li>
    </ul>

    <br>
    @yield ('sub-content')
@stop
