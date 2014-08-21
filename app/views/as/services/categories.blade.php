@extends ('as.layout')

@section ('sub-content')
<h4 class="comfortaa">Lisää kategoria</h4>
{{ Form::open(['route' => 'as.services.categories', 'class' => 'form-horizontal well', 'role' => 'form']) }}
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Nimi</label>
        <div class="col-sm-5">
            <input type="text" class="form-control input-sm" id="name">
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Kuvaus</label>
        <div class="col-sm-5">
            <textarea rows="10" class="form-control input-sm" id="description"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label><input type="checkbox"> Varattavissa kuluttajille</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}

<h4 class="comfortaa">Kaikki kategoriat</h4>
<form action="" class="form-inline">
<table class="table table-hover">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Kategorian nimi</th>
            <th>Varattavissa kuluttajille</th>
            <th>Kuvaus</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><input type="checkbox"></td>
            <td>Service 1</td>
            <td>On</td>
            <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident consequuntur odio velit beatae reprehenderit placeat, exercitationem consectetur veritatis ratione ducimus molestias, doloribus molestiae reiciendis praesentium dolorum sequi, eveniet pariatur qui.</td>
            <td>
                <a href="#" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                <a href="#" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">
                <div class="form-group">
                    <label>Valitse toiminto</label>
                    <select name="" id="" class="form-control input-sm">
                        <option value="">Delete</option>
                        <option value="">Blahde</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">{{ trans('common.save') }}</button>
            </td>
            <td colspan="5" class="text-right">
                <div class="dropdown">
                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                    Yksiköitä yhteensä <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#">5</a></li>
                        <li><a href="#">10</a></li>
                        <li><a href="#">20</a></li>
                        <li><a href="#">50</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    </tfoot>
</table>
</form>
@stop
