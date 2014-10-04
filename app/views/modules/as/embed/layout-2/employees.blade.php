<div class="as-employees col-sm-3" id="as-service-{{ $service->id }}-employees">
    <h5>{{ trans('as.embed.layout_2.employees') }}</h5>
    <div class="better">
        <div class="as-employees-row">
            <p><a href="#" class="as-employee">{{ trans('common.anyone') }}</a></p>
        @foreach ($employees as $employee)
            <p><a href="#" class="as-employee">{{ $employee->name }}</a></p>
        @endforeach
        </div>
    </div>
</div>
