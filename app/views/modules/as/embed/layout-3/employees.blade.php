<div class="as-employee">
    <label>
        <input type="radio" name="employee_id" value="-1">
        {{ trans('common.anyone') }}
    </label>
</div>
@foreach ($employees as $employee)
<div class="as-employee">
    <label>
        <input type="radio" name="employee_id" value="{{ $employee->id }}">
        {{ $employee->name }}
    </label>
</div>
@endforeach
