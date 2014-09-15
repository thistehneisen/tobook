<h3>{{{ $business->full_name }}}</h3>
<p>{{{ $business->full_address }}}</p>

<div class="row">
    <div class="col-sm-8">
        <p><img src="//placehold.it/500x300" alt="" class="img-responsive img-rounded"></p>

        <h4>About {{ $business->full_name }}</h4>
        <p>{{{ $business->description }}}</p>

        <table class="table table-stripped table-hovered">
            <thead>
                <tr>
                    <th>Palvelu</th>
                    <th>Ajankota</th>
                    <th>Hinta</th>
                    <th></th>
                    <th>Osta</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="#" title="">Service name</a></td>
                    <td>La 10:10 klo</td>
                    <td>50&euro; (75&euro;)</td>
                    <td>
                        <p class="text-danger"><strong>-33%</strong></p>
                    </td>
                    <td>
                        <a href="#" class="btn btn-success btn-sm">Varaa</a>
                    </td>
                </tr>
                <tr>
                    <td><a href="#" title="">Service name</a></td>
                    <td>La 10:10 klo</td>
                    <td>50&euro; (75&euro;)</td>
                    <td>
                        <p class="text-danger"><strong>-33%</strong></p>
                    </td>
                    <td>
                        <a href="#" class="btn btn-success btn-sm">Varaa</a>
                    </td>
                </tr>
                <tr>
                    <td><a href="#" title="">Service name</a></td>
                    <td>La 10:10 klo</td>
                    <td>50&euro; (75&euro;)</td>
                    <td>
                        <p class="text-danger"><strong>-33%</strong></p>
                    </td>
                    <td>
                        <a href="#" class="btn btn-success btn-sm">Varaa</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-sm-4">
        <div class="box text-center">
            <a href="#" class="btn btn-success btn-lg">Varaa</a>
        </div>

        <div class="box">
            <h4>Locations &amp; Hours</h4>
            <div class="text-center" style="min-height: 150px;" id="js-map-{{ $business->id }}" data-lat="{{ $business->lat }}" data-lng="{{ $business->lng }}">
                <i class="fa fa-spinner fa-spin fa-3x text-muted"></i>
            </div>

            <h5>Business hours</h5>
            <table class="table">
                <tbody>
                    <tr>
                        <td>Mon</td>
                        <td>9:00 &ndash; 16:00</td>
                    </tr>
                    <tr>
                        <td>Mon</td>
                        <td>9:00 &ndash; 16:00</td>
                    </tr>
                    <tr>
                        <td>Mon</td>
                        <td>9:00 &ndash; 16:00</td>
                    </tr>
                    <tr>
                        <td>Mon</td>
                        <td>9:00 &ndash; 16:00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
