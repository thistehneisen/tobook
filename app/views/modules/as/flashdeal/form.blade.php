<div id="flashdeal-form" class="as-flashdeal-form">
    <h2>{{ trans('as.flashdeal.flashdeal') }}</h2>
    <form id="flash_deal_form">
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
                                    <label for="description" class="col-sm-4 control-label">{{ trans('as.flashdeal.services') }}</label>
                                    <div class="col-sm-8">
                                        @foreach ($services as $service)
                                        <div class="checkbox">
                                            <label for="service-{{ $service->id }}">{{ Form::checkbox('services[]', $service->id,((bool)$okServices[$service->id]), ['class' => 'input-md', 'id' => 'service-' . $service->id, ((!(bool)$okServices[$service->id]) ? 'checked' : null)]) }} {{ $service->name }} - {{ $service->formattedLength }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="description" class="col-sm-4 control-label">{{ trans('as.flashdeal.discount_percentage') }}</label>
                                    <div class="col-sm-8">
                                         {{ Form::text('discount_percentage', 15, ['class' => 'form-control input-sm spinner', 'id' => 'discount_percentage', 'name' => 'discount_percentage']) }}
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
                                    <div class="col-sm-12">
                                        <a id="btn-add-flash-deal" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
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
    $(document).on('click', '#btn-add-flash-deal', function(e) {
        e.preventDefault();
        action_url = $('#upsert_flash_deal_url').val();
        $.ajax({
            type: 'POST',
            url: action_url,
            data: $('#flash_deal_form').serialize(),
            dataType: 'json'
        }).done(function (data) {
            if (data.success) {
                location.reload();
            } else {
                alertify.alert(data.message);
            }
        });
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

  $("input[name='discount_percentage']").TouchSpin({
        min: 0,
        max: 100,
        step: 1,
        decimals: 0,
        boostat: 15,
        maxboostedstep: 10,
        postfix: '%'
    });
});
</script>
<input type="hidden" id="upsert_flash_deal_url" value="{{ route('as.flashdeal.upsert') }}"/>
