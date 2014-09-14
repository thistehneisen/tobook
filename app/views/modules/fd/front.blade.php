    <div class="row">
        <h3 class="comfortaa">{{ trans('fd.index') }}</h3>
        @foreach ($deals as $category)
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $category->name }}</div>
                <ul class="list-group">
                    @foreach ($category->deals as $deal)
                    <li class="list-group-item">
                        <div class="flashdeal-item text-left">
                            <h4 class="text-center">Mikael Parturi <span class="orange">-{{ $deal->flashDeal->discount_percent }}%</span></h4>
                            <p>Malminkatu 8</p>
                            <p>{{ $deal->flashDeal->discounted_price }}&euro;</p>
                            <p>{{ $deal->expire->format(trans('common.format.date')) }} <a href="#" class="btn btn-orange">{{ $deal->expire->format(trans('common.format.time')) }}</a></p>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="panel-footer">Explore more</div>
            </div>
        </div>
        @endforeach
    </div>
