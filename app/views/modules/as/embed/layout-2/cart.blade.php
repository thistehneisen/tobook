<div class="panel panel-default">
    <div class="panel-heading to-upper"><strong>{{ trans('as.embed.layout_2.selected') }}</strong></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="item better">
                    <h4>
                        <strong>Service 1</strong>
                        <a href="#" class="pull-right"><i class="glyphicon glyphicon-remove text-danger"></i></a>
                    </h4>
                    <h5>Employee 1</h5>
                    <dl class="dl-horizontal">
                        <dt>Päivämäärä</dt>
                        <dd>7th lokakuu 2014, 10:00am</dd>
                        <dt>Hinta</dt>
                        <dd>&euro;10.00</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading to-upper"><strong>{{ trans('as.embed.layout_2.form') }}</strong></div>
    <div class="panel-body">
        @include ('modules.as.embed.layout-2.checkout')

        <div class="row">
            <dic class="col-sm-12">
                <a id="btn-book" href="#" class="btn btn-default">{{ trans('as.embed.cancel') }}</a>
                <a id="btn-book" href="#" class="btn btn-success pull-right">{{ trans('as.embed.book') }}</a>
            </dic>
        </div>
    </div>
</div>

