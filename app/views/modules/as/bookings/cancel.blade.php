<!doctype html>
<html>
<head>
    <title>Cancel Booking</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid">
    @if(!empty($error))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-warning">
                {{ $error }}
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                {{ nl2br($message) }}
            </div>
        </div>
    </div>
     @endif
</div>
</body>
</html>
