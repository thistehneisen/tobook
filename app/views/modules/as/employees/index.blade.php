@extends ('modules.as.layout')

@section ('content')
<div class="row">
    <div class="col-md-6">
        <form class="form-inline" role="form">
            <div class="input-group">
              <input type="text" class="form-control input-sm" placeholder="Haku">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
              </span>
            </div><!-- /input-group -->
        </form>
    </div>
    <div class="col-md-6 text-right">
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn active btn-default">Kaikki</button>
            <button type="button" class="btn btn-default">Aktiivinen</button>
            <button type="button" class="btn btn-default">Ei aktiivinen</button>
        </div>
    </div>
</div>

<form class="form-inline" role="form">
<table class="table table-hover">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Kuva</th>
            <th>Työntekijät nimi</th>
            <th>Sähköpostiosoite</th>
            <th>Puhelinnumero</th>
            <th>Palvelut</th>
            <th>Tila</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $employee)
        <tr>
            <td><input type="checkbox"></td>
            <td><img src=""></td>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->email }}</td>
            <td>{{ $employee->phone }}</td>
            <td>{{ $employee->service }}</td>
            <td>{{ $employee->is_active }}</td>
            <td>
                <a href="{{ route('as.employees.edit', ['id'=> $employee->id ]) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                <a href="{{ route('as.employees.upsert', ['id'=> $employee->id ]) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        @endforeach
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
