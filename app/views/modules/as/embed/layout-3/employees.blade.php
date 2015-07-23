<div class="as-employee">
    <label class="col-lg-12 col-sm-6 col-md-6 btn btn-default">
        <input class="hidden" type="radio" name="employee_id" value="-1">
        {{ trans('common.anyone') }}
    </label>
</div>
@foreach ($employees as $employee)
<div class="as-employee">
    <label class="col-lg-12 col-sm-6 col-md-6 btn btn-default">
        <input type="radio" class="hidden" name="employee_id" value="{{ $employee->id }}">
        {{ $employee->name }}
    </label>
</div>
@endforeach
