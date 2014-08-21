@extends ('modules.as.layout')

@section ('sub-content')
<div class="alert alert-info">
    <p><strong>Lisää palvelu</strong></p>
    <p>Lisää uusi palvelu lisäämällä palvelun nimi, palvelun kesto ja työntekijät</p>
</div>

{{ Form::open(['route' => 'as.services.create', 'class' => 'form-horizontal', 'role' => 'form']) }}
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Nimi</label>
        <div class="col-sm-5">
            <input type="text" class="form-control input-sm" id="name">
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Kuvaus</label>
        <div class="col-sm-5">
            <textarea class="form-control input-sm" id="description"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="price" class="col-sm-2 control-label">Hinta</label>
        <div class="col-sm-5">
            <div class="input-group">
                <span class="input-group-addon">&euro;</span>
                <input type="text" class="form-control input-sm" placeholder="">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="duration" class="col-sm-2 control-label">Kesto</label>
        <div class="col-sm-5">
            <input type="number" class="form-control input-sm" id="duration">
        </div>
    </div>
    <div class="form-group">
        <label for="before" class="col-sm-2 control-label">Ennen</label>
        <div class="col-sm-5">
            <input type="number" class="form-control input-sm" id="before">
        </div>
    </div>
    <div class="form-group">
        <label for="after" class="col-sm-2 control-label">Jälkeen</label>
        <div class="col-sm-5">
            <input type="number" class="form-control input-sm" id="after">
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">Yhteensä</label>
        <div class="col-sm-5">
            <input type="number" class="form-control input-sm" id="total" value="0" disabled>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label><input type="checkbox"> Tila?</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">Kategoria</label>
        <div class="col-sm-5">
            <select name="" id="" class="form-control input-sm">
                <option value="">Category 1</option>
                <option value="">Category 1</option>
                <option value="">Category 1</option>
                <option value="">Category 1</option>
                <option value="">Category 1</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">Resurssit</label>
        <div class="col-sm-5">
            <select name="" id="" class="form-control input-sm">
                <option value="">Category 1</option>
                <option value="">Category 1</option>
                <option value="">Category 1</option>
                <option value="">Category 1</option>
                <option value="">Category 1</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">Lisäpalvelut</label>
        <div class="col-sm-5">
            <select name="" id="" class="form-control input-sm">
                <option value="">Category 1</option>
                <option value="">Category 1</option>
                <option value="">Category 1</option>
                <option value="">Category 1</option>
                <option value="">Category 1</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label">Työntekijät</label>
        <div class="col-sm-5">
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-sm-6">
                    <div class="checkbox">
                    <label for=""><input type="checkbox"> Employee 1</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <select name="" id="" class="form-control input-sm">
                        <option value="">Category 1</option>
                        <option value="">Category 1</option>
                        <option value="">Category 1</option>
                        <option value="">Category 1</option>
                        <option value="">Category 1</option>
                    </select>
                </div>
            </div>

            <div class="row" style="margin-bottom: 5px;">
                <div class="col-sm-6">
                    <div class="checkbox">
                    <label for=""><input type="checkbox"> Employee 1</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <select name="" id="" class="form-control input-sm">
                        <option value="">Category 1</option>
                        <option value="">Category 1</option>
                        <option value="">Category 1</option>
                        <option value="">Category 1</option>
                        <option value="">Category 1</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary">Tallenna</button>
        </div>
    </div>
{{ Form::close() }}
@stop
