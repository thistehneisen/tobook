@extends ('modules.as.embed.embed')

@section ('content')
<div class="container-fluid">
    <!-- Sidebar -->
    <div class="col-sm-3">
        <div class="panel panel-default">
            <div class="panel-heading">Select a date</div>
            <div class="panel-body">
                <div id="datepicker"></div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Selected services</div>
            <div class="panel-body">
                <div class="alert alert-info">Cart is empty</div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">Select a service</div>
            <div class="panel-body">

                <div class="list-group">
                @foreach ($categories as $category)
                    <div class="list-group-item">
                        <h4 class="list-group-item-heading">{{ $category->name }}</h4>
                        <div class="list-group-item-text">
                            <p class="text-muted">{{ $category->description }}</p>

                            <div class="services" id="category-services-{{ $category->id }}">
                            @if ($category->services->isEmpty())
                                <p class="text-muted"><em>There are no services for this category</em></p>
                            @endif
                            @foreach ($category->services as $service)
                                <div class="single">
                                    <a data-toggle="collapse" data-parent="#category-services-{{ $category->id }}" href="#service-{{ $category->id.'-'.$service->id }}"><h5 class="heading">{{ $service->name }}</h5></a>
                                    <div id="service-{{ $category->id.'-'.$service->id }}" class="collapse">
                                        <p>
                                            <button disabled class="btn btn-default"><i class="glyphicon glyphicon-tag"></i> &euro;{{ number_format($service->price) }}</button>
                                            <button disabled class="btn btn-default"><i class="glyphicon glyphicon-time"></i> {{ $service->length }} minutes</button>
                                            <span class="text-muted">{{ $service->description }}</span>
                                        </p>
                                        <a href="#form-add-service" class="btn btn-success btn-fancybox">Availability</a>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

<div id="form-add-service" style="display: none;">
    <form action="">
        <div class="form-group">
            <label>Haluaisitko myös varata?</label>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="abc" value="1"> Extra service 1 (20 mins)
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="abc" value="1"> Extra service 1 (20 mins)
                </label>
            </div>
        </div>
        <input type="hidden" name="date" id="txt-date">
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary">Next</button>
        </div>
    </form>
</div>
@stop
