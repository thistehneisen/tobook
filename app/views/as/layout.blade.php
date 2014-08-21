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
    <ul class="nav nav-pills" role="tablist">
        <li class="active"><a href="#"><i class="fa fa-calendar"></i> Kalenteri</a></li>
        <li><a href="#"><i class="fa fa-bookmark"></i> Varaukset</a></li>
        <li><a href="#"><i class="fa fa-wrech"></i> Palvelut</a></li>
        <li><a href="#"><i class="fa fa-users"></i> Työntekijät</a></li>
        <li><a href="#"><i class="fa fa-cog"></i> Asetukset</a></li>
        <li><a href="#"><i class="fa fa-signal"></i> Raportit</a></li>
        <li><a href="#"><i class="fa fa-arrow-down"></i> Asenna</a></li>
        <li><a href="#"><i class="fa fa-desktop"></i> Esikatselu</a></li>
    </ul>

    <br>
    @yield ('sub-content')
@stop
