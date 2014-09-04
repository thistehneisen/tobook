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

            <div class="list-group-item">
              <h4 class="list-group-item-heading">Category 1</h4>
              <div class="list-group-item-text">
                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus nihil voluptatem animi ratione, minus placeat impedit vel quo quos, sed rem, delectus explicabo nemo officiis eos! Incidunt ullam, harum earum.</p>

                <div class="services" id="services-list-1">
                  <div class="single">
                    <a data-toggle="collapse" data-parent="#services-list-1" href="#service-1"><h5 class="heading">Service 1</h5></a>
                    <div id="service-1" class="collapse">
                      <p>
                        <button disabled class="btn btn-default"><i class="glyphicon glyphicon-tag"></i> &euro;10.00</button>
                        <button disabled class="btn btn-default"><i class="glyphicon glyphicon-time"></i> 90 minutes</button>
                        <span class="text-muted">Service description</span>
                      </p>
                      <a href="#form-add-service" class="btn btn-success btn-fancybox">Availability</a>
                    </div>
                  </div>
                  <div class="single">
                    <a data-toggle="collapse" data-parent="#services-list-1" href="#service-2"><h5 class="heading">Service 1</h5></a>
                    <div id="service-2" class="collapse">
                      <p>
                        <button disabled class="btn btn-default"><i class="glyphicon glyphicon-tag"></i> &euro;10.00</button>
                        <button disabled class="btn btn-default"><i class="glyphicon glyphicon-time"></i> 90 minutes</button>
                        <span class="text-muted">Service description</span>
                      </p>
                      <a href="#form-add-service" class="btn btn-success btn-fancybox">Availability</a>
                    </div>
                  </div>
                  <div class="single">
                    <a data-toggle="collapse" data-parent="#services-list-1" href="#service-3"><h5 class="heading">Service 1</h5></a>
                    <div id="service-3" class="collapse">
                      <p>
                        <button disabled class="btn btn-default"><i class="glyphicon glyphicon-tag"></i> &euro;10.00</button>
                        <button disabled class="btn btn-default"><i class="glyphicon glyphicon-time"></i> 90 minutes</button>
                        <span class="text-muted">Service description</span>
                      </p>
                      <a href="#form-add-service" class="btn btn-success btn-fancybox">Availability</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

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
