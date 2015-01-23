<!doctype html>
<html>
<head>
    <title>Cancel Booking</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.2/css/bootstrap.min.css">
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
    @elseif(!empty($confirm))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-warning">
                {{ $confirm }}
               <a href="{{ route('as.bookings.doCancel', ['uuid' => $uuid]) }}" class="btn btn-primary">{{ trans('common.yes') }}</a>
            </div>
        </div>
    </div>
    @elseif(!empty($message))
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
