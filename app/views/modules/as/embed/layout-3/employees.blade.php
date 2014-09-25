@foreach ($employees as $employee)
<div class="as-employee">
    <label>
        <input type="radio" name="employee_id" value="{{ $employee->id }}">
        <img src="//placehold.it/30x30" alt="{{ $employee->name }}" class="img-circle img-thumbnail">
        {{ $employee->name }}
    </label>
</div>
@endforeach
