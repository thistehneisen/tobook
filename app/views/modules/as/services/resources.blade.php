@extends ('modules.as.layout')

@section ('sub-content')

@include('modules.as.services.resource.tabs')

<h4 class="comfortaa">Kaikki resurssit</h4>
<form action="" class="form-inline form-table">
<table class="table table-hover">
    <thead>
        <tr>
             <th><input type="checkbox" class="toggle-check-all-boxes" data-checkbox-class="checkbox"></th>
            <th>Kategorian nimi</th>
            <th>Varattavissa kuluttajille</th>
            <th>Kuvaus</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
         @foreach ($resources as $resource)
        <tr>
            <td><input type="checkbox" class="checkbox" name="resources[]" value="{{ $resource->id }}"></td>
            <td>{{ $resource->name }}</td>
            <td>{{ $resource->quanity }}</td>
            <td>{{ $resource->description }}</td>
            <td>
                <a href="{{ route('as.services.resources.edit', ['id'=> $resource->id ]) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                <a href="{{ route('as.services.resources.delete', ['id'=> $resource->id ]) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        @endforeach
        @if (empty($resources->getTotal()))
        <tr>
            <td colspan="4">{{ trans('common.no_records') }}</td>
        </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">
                <div class="form-group">
                    <label>Valitse toiminto</label>
                    <select name="" id="mass-action" class="form-control input-sm">
                        <option value="{{ route('as.services.resources.destroy') }}" data-action-name="delete all selected resources">Delete</option>
                        <option value="">Blahde</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm btn-submit-mass-action">{{ trans('common.save') }}</button>
            </td>
            <td colspan="5" class="text-right">
                <div class="dropdown">
                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                    Yksiköitä yhteensä <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ route('as.services.resources', ['perPage' => 5]) }}">5</a></li>
                        <li><a href="{{ route('as.services.resources', ['perPage' => 10]) }}">10</a></li>
                        <li><a href="{{ route('as.services.resources', ['perPage' => 20]) }}">20</a></li>
                        <li><a href="{{ route('as.services.resources', ['perPage' => 50]) }}">50</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    </tfoot>
</table>
</form>
@stop
