<div id="flashdeal-form" class="as-flashdeal-form">
    <h2>{{ trans('as.flashdeal.flashdeal') }}</h2>
    <form id="freetime_form">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">{{ trans('as.flashdeal.add_flashdeal') }}</a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="description" class="col-sm-4 control-label">{{ trans('as.flashdeal.master_category') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('master_category', $master_categories, null, ['class' => 'form-control input-sm', 'id' => 'master_category']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-sm-4 control-label">{{ trans('as.flashdeal.services') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::select('services', [], null, ['class' => 'form-control input-sm', 'id' => 'services']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-sm-4 control-label">{{ trans('as.flashdeal.discounted_price') }}</label>
                                    <div class="col-sm-8">
                                         {{ Form::text('discounted_price', '', ['class' => 'datepicker form-control input-sm', 'id' => 'discounted_price']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="from_date" class="col-sm-4 control-label">{{ trans('as.flashdeal.start_date') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('start_date', $bookingDate, ['class' => 'form-control input-sm disabled', 'id' => 'start_date']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="from_date" class="col-sm-4 control-label">{{ trans('as.flashdeal.start_time') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('start_time', $startTime, ['class' => 'form-control input-sm disabled', 'id' => 'start_time']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="from_date" class="col-sm-4 control-label">{{ trans('as.flashdeal.expire') }}</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('expire', $bookingDate, ['class' => 'datepicker form-control input-sm', 'id' => 'expire']) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <a id="btn-add-employee-freetime" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row -->
                    </div> <!-- panel body -->
                </div><!-- end collapseOne -->
            </div> <!-- end panel-default -->
        </div><!--  end panel-group -->
    </form>
</div>
<script>
$(function () {
    $(document).on('change', '#master_category', function () {
        var master_category_id = $(this).val(),
            $services = $('#services'),
            employee_id = $('#employee_id').val();;

        if (master_category_id !== '-1' && master_category_id !== '') {
            $.ajax({
                url: $('#get_services_from_master_category_url').val(),
                data: {
                    master_category_id: master_category_id,
                    employee_id : employee_id
                },
                dataType: 'json'
            }).done(function (data) {
                $services.empty();

                $.each(data, function (index, value) {
                    $services.append(
                        $('<option>', {
                            value: value.id,
                            text: value.name
                        })
                    );
                });
            });
        } else {
            $services.empty();
        }
    });

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: new Date(),
        todayBtn: true,
        todayHighlight: true,
        weekStart: 1,
        autoclose: true,
        language: '{{ App::getLocale() }}',
    });
});
</script>
