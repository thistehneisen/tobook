 <div class="form-group row show-on-thankyou">
    <div class="col-sm-6 col-sm-offset-2 message">
        <h4 style="text-transform: uppercase">{{ str_replace(['<h2>','</h2>'],'', trans('as.embed.success_line1')) }} {{ str_replace(['<h3>','</h3>'],'', trans('as.embed.success_line2')) }}</h4>
        <h5>{{ str_replace(['<h3>','</h3>'],'', trans('as.embed.success_line3')) }}</h5>
    </div>
    <div class="col-sm-2 icon">
        <i class="fa fa-check-circle fa-4x"></i>
    </div>
</div>
