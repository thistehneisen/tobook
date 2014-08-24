@extends ('modules.as.layout')

@section ('content')
<div class="alert alert-info">
    <p><strong>Lisää palvelu</strong></p>
    <p>Lisää uusi palvelu lisäämällä palvelun nimi, palvelun kesto ja työntekijät</p>
</div>

{{ Form::open(['route' => 'as.services.create', 'class' => 'form-horizontal well', 'role' => 'form']) }}
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Nimi</label>
        <div class="col-sm-5">
           {{ Form::text('name', (isset($service)) ? $service->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Kuvaus</label>
        <div class="col-sm-5">
            {{ Form::textarea('description', (isset($service)) ? $service->description:'', ['class' => 'form-control input-sm', 'id' => 'description']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="price" class="col-sm-2 control-label">Hinta</label>
        <div class="col-sm-5">
            <div class="input-group">
                <span class="input-group-addon">&euro;</span>
                {{ Form::text('price', (isset($service)) ? $service->price:'', ['class' => 'form-control input-sm', 'id' => 'price']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="duration" class="col-sm-2 control-label">Kesto</label>
        <div class="col-sm-5">
            {{ Form::text('length', (isset($service)) ? $service->length :'', ['class' => 'form-control input-sm spinner', 'id' => 'length']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="before" class="col-sm-2 control-label">Ennen</label>
        <div class="col-sm-5">
            {{ Form::text('before', (isset($service)) ? $service->before:'', ['class' => 'form-control input-sm spinner', 'id' => 'before']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="after" class="col-sm-2 control-label">Jälkeen</label>
        <div class="col-sm-5">
            {{ Form::text('after', (isset($service)) ? $service->after:'', ['class' => 'form-control input-sm spinner', 'id' => 'after']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">Yhteensä</label>
        <div class="col-sm-5">
           {{ Form::text('total', (isset($service)) ? $service->total:'', ['class' => 'form-control input-sm', 'id' => 'total', 'disabled'=>'disabled']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">Kategoria</label>
        <div class="col-sm-5">
            {{ Form::select('category_id', array(0=> trans('common.options_select'))+$categories, isset($service) ? $service->category_id :0, ['class' => 'form-control input-sm', 'id' => 'category']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">Tila</label>
        <div class="col-sm-5">
           {{ Form::select('is_active', array(-1=> trans('common.options_select'))+['0'=> trans('common.active'),'1'=> trans('common.inactive')], isset($service) ? $service->is_active : -1, ['class' => 'form-control input-sm', 'id' => 'is_active']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">Resurssit</label>
        <div class="col-sm-5">
           {{ Form::select('resource', array(0=> trans('common.options_select'))+$resources, 1, ['class' => 'form-control input-sm', 'id' => 'resource']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">Lisäpalvelut</label>
        <div class="col-sm-5">
            {{ Form::select('extra', $extras, 1, ['class' => 'form-control input-sm', 'id' => 'extra']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label">Työntekijät</label>
        <div class="col-sm-5">
            @foreach ($employees as $employee)
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-sm-6">
                    <div class="checkbox">
                    <label for="">{{ Form::checkbox('employees[]', 1); }} {{ $employee->name}}</label>
                    </div>
                </div>
                <div class="col-sm-6">
                   {{ Form::select("plustimes[$employee->id]", array(5, 10, 15, 30), 0, ['class' => 'form-control input-sm', 'id' => 'plustime']) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary">Tallenna</button>
        </div>
    </div>
{{ Form::close() }}
@stop
