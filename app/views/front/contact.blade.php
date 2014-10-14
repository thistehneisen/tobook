@extends ('layouts.default')

@section('styles')
    {{ HTML::style('assets/css/intro.css') }}
@stop

@section('main-classes') intro container @stop

@section('content')
<div class="row-fluid" style="margin-top: 30px">
    <div class="col-lg-6 col-sm-6 well">
        <p><i class="fa fa-rocket"></i> <strong>Varaa.com Digital Oy</strong></p>
        <p><i class="fa fa-building"></i> Malmin kauppatie 8 B, 00700 Helsinki, FINLAND</p>
        <p><i class="fa fa-envelope"></i> <a href="mailto:asiakaspalvelu@varaa.com">asiakaspalvelu@varaa.com</a></p>
        <p><i class="fa fa-phone"></i> +358 45 146 3755</p>
    </div>
    <div class="col-lg-6 col-sm-6">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7919.317412946968!2d25.00657736560668!3d60.24974104031232!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x469208672712c5c3%3A0x8cfd3a2fe21e7ec5!2sMalmin+kauppatie+8%2C+00700+Helsinki!5e0!3m2!1sen!2sfi!4v1413256472486" width="600" height="450" frameborder="0" style="border:0"></iframe>
    </div>
</div>
@stop
