<div class="as-employees col-sm-3" id="as-service-{{ $service->id }}-employees">
    <h5>{{ trans('as.embed.layout_2.employees') }} </h5>
    <div class="better">
        <div class="as-employees-row">
            <p><a href="#" class="as-employee" id="btn-employee-0"><small><i class="glyphicon glyphicon-user"></i></small> {{ trans('common.anyone') }}</a></p>
        @foreach ($employees as $employee)
            <p><a data-employee-id="{{ $employee->id }}" href="#" class="as-employee" id="btn-employee-{{ $employee->id }}"><small><i class="glyphicon glyphicon-user"></i></small> {{ $employee->name }}</a></p>
        @endforeach
        </div>
    </div>
    <div class="as-loading" style="display:none">
        <i class="glyphicon glyphicon-refresh text-info"></i> {{ trans('as.embed.loading') }}
    </div>
</div>
