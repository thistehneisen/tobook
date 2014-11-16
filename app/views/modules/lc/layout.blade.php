@extends ('layouts.dashboard')

@section ('styles')
    @parent
    {{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
    {{ HTML::style(asset('packages/alertify/css/themes/default.min.css')) }}
    <style>
    .main { margin: 0 auto; }
    </style>
@stop

@section ('scripts')
    @parent
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}

    <script>
    $(function() {
        $('table.table-crud').find('a.btn-danger').click('on', function(event) {
            event.preventDefault();
            var $this = $(this);

            alertify.confirm('Confirm', '{{ trans('common.are_you_sure') }}',
              function(){
                window.location = $this.attr('href');
              },
              function(){
                //do cancel action
            });
        });
    });
    </script>
@stop

@section ('nav-admin')
<nav class="navbar as-main-nav" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#admin-menu">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="admin-menu">
            <ul class="nav navbar-nav nav-admin nav-as">
                <li><a href="{{ route('lc.consumers.index') }}"><i class="fa fa-group"></i> {{ trans('loyalty-card.consumer.index') }}</a></li>
                <li><a href="{{ route('lc.offers.index') }}"><i class="fa fa-gift"></i> {{ trans('loyalty-card.offer.index') }}</a></li>
                <li><a href="{{ route('lc.vouchers.index') }}"><i class="fa fa-money"></i> {{ trans('loyalty-card.voucher.index') }}</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@stop
