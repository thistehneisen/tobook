<!doctype html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css">
    {{ HTML::style(asset('packages/alertify/alertify.core.css')) }}
    {{ HTML::style(asset('packages/alertify/alertify.bootstrap.css')) }}
    <link rel="stylesheet" href="{{ asset('assets/css/as/layout-1.css') }}">
</head>
<body>
    @yield('content')

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
    <script src="{{ asset('assets/js/as/embed.js') }}"></script>
    <script>
    $(document).ready(function(){
        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date(),
            todayBtn: true,
            todayHighlight: true
        }).on('changeDate', function (e) {
            if(window.location.href.indexOf('date') != -1){
                window.location.href = window.location.href.replace(new RegExp("date=.*?(&|$)", 'g'), "date=" + e.format());
            } else {
                 window.location.href = window.location.href + '?date=' + e.format();
            }
        });
        $("#datepicker").datepicker("update", new Date({{ $date->year }},{{ $date->month - 1}},{{ $date->day }}));
    });
    </script>
</body>
</html>
