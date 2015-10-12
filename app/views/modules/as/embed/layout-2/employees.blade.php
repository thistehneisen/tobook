<div class="as-employees col-sm-3" id="as-service-{{ $service->id }}-employees" name="employees">
    <h5>
        @if (empty($user->asOptions['factor']))
            {{ trans('as.embed.layout_2.employees') }}
        @else
            {{ $user->asOptions['factor'] }}
        @endif
    </h5>
    <div class="better">
        <div class="js-loading"><i class="fa fa-refresh fa-spin fa-5x"></i></div>
        <div class="as-employees-row">
            <p><a href="#" class="as-employee" id="btn-employee-0"><small><i class="glyphicon glyphicon-user"></i></small> {{ trans('common.anyone') }}</a></p>
        @foreach ($employees as $employee)
            <p><a data-employee-id="{{ $employee->id }}" href="#" class="as-employee" id="btn-employee-{{ $employee->id }}"><small><i class="glyphicon glyphicon-user"></i></small> {{ $employee->name }}</a></p>
        @endforeach
        </div>
    </div>
</div>
