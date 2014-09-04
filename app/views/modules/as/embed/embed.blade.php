<!doctype html>
<html>
<head>
  <title></title>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/as/layout-1.css') }}">
</head>
<body>
  <div class="container-fluid">
    <!-- Sidebar -->
    <div class="col-sm-3">
      <div class="panel panel-default">
        <div class="panel-heading">Select a date</div>
        <div class="panel-body">
          <div class="datepicker"></div>
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

      <div class="form-group text-right">
        <button type="submit" class="btn btn-primary">Next</button>
      </div>
    </form>
  </div>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
  <script src="{{ asset('assets/js/as/embed.js') }}"></script>
</body>
</html>
