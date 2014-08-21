@extends ('as.layout')

@section ('sub-content')
<div class="alert alert-info">
    <p><strong>Etusivu</strong></p>
    <p>Näkymässä näet kaikkien työntekijöiden kalenterin. Kuluttajille varattavat ajat vihreällä. Voit tehdä halutessasi varauksia myös harmaalle alueelle joka näkyy kuluttajille suljettuna.</p>
</div>

<div class="row">
    <div class="col-md-2">
        <div class="input-group">
            <input type="text" class="form-control date-picker">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-9">
        <button type="button" class="btn btn-default">Tänään</button>
        <button type="button" class="btn btn-default">Huomenna</button>

        <div class="btn-group">
            <button class="btn btn-link"><i class="fa fa-fast-backward"></i></button>
            <button class="btn btn-link"><i class="fa fa-backward"></i></button>
            <button class="btn btn-link"><i class="fa fa-forward"></i></button>
            <button class="btn btn-link"><i class="fa fa-fast-forward"></i></button>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-default">Ma</button>
            <button type="button" class="btn btn-default">Ti</button>
            <button type="button" class="btn btn-default">Ke</button>
            <button type="button" class="btn btn-default btn-primary">To</button>
            <button type="button" class="btn btn-default">Pe</button>
            <button type="button" class="btn btn-default">La</button>
            <button type="button" class="btn btn-default">Su</button>
        </div>
    </div>

    <div class="col-md-1">
        <button class="btn btn-primary"><i class="fa fa-print"> Tulosta</i></button>
    </div>
</div>

<div class="row row-no-padding">
    <h3 class="comfortaa">Good to know</h3>
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
        <table class="table table-condensed as-table-left-header ">
        <thead>
            <tr>
                <td>&nbsp;</td>
            </tr>
            </thead>
            <tr>
                <td>08:00</td>
            </tr>
            <tr>
                <td>09:00</td>
            </tr>
            <tr>
                <td>10:00</td>
            </tr>
            <tr>
                <td>11:00</td>
            </tr>
            <tr>
                <td>12:00</td>
            </tr>
            <tr>
                <td>13:00</td>
            </tr>
            <tr>
                <td>14:00</td>
            </tr>
            <tr>
                <td>15:00</td>
            </tr>
            <tr>
                <td>16:00</td>
            </tr>
            <tr>
                <td>17:00</td>
            </tr>
            <tr>
                <td>18:00</td>
            </tr>
            <tr>
                <td>19:00</td>
            </tr>
            <tr>
                <td>20:00</td>
            </tr>
            <tr>
                <td>21:00</td>
            </tr>
            <tr>
                <td>22:00</td>
            </tr>
            <tr>
                <td>23:00</td>
            </tr>
        </table>
    </div>
    <div class="table-responsive as-table-wrapper col-lg-11 col-md-11 col-sm-11 col-xs-11">
    <table class="table table-condensed as-table">
        <thead>
            <tr>
                <th>User 1</th>
                <th>User 2</th>
                <th>User 3</th>
                <th>User 4</th>
                <th>User 5</th>
                <th>User 6</th>
                <th>User 7</th>
                <th>User 7</th>
                <th>User 7</th>
                <th>User 7</th>
                <th>User 7</th>
            </tr>
        </thead>
        <tbody>
            <tr class="inactive">
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr class="inactive">
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td class="booked">
                    Hung (Service 3)
                    <a href="#" class="pull-right"><i class="fa fa-plus"></i></a>
                </td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td class="booked"></td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td class="booked"></td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td class="booked"></td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr class="inactive">
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>
            <tr class="inactive">
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
                <td><small>8:00</small> varaa</td>
            </tr>

        </tbody>
    </table>
    </div>
</div>
@stop
