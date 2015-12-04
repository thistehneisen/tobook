<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', trans('common.home')) :: {{{ Settings::get('meta_title') }}}</title>
    <!-- Bootstrap -->

    {{ HTML::style('//fonts.googleapis.com/css?family=Bitter:400,300,600') }}
    {{ HTML::style('//fonts.googleapis.com/css?family=Comfortaa:400,300,700') }}
    {{ HTML::style('//fonts.googleapis.com/css?family=Droid+Serif:400,300,700') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css') }}
    @yield('styles')
</head>
<body class="@yield('body_class')">
    @yield('content')

    
    @yield('scripts')
    {{ Settings::get('bottom_script') }}
</body>
</html>
