<div class="form-group row show-on-thankyou" @if($hidden) style="display: none" @endif>
    <div class="col-sm-8 col-sm-offset-2">
        <table class="table table-striped">
            <tbody>
                <tr class="cart-detail" id="cart-detail-30958">
                    <td class="message">
                        <h4 style="text-transform: uppercase">{{ str_replace(['<h2>','</h2>'],'', trans('as.embed.success_line1')) }} {{ str_replace(['<h3>','</h3>'],'', trans('as.embed.success_line2')) }}</h4>
                        <h5>{{ str_replace(['<h3>','</h3>'],'', trans('as.embed.success_line3')) }}</h5>
                    </td>
                    <td class="icon">
                        <i class="fa fa-check-circle fa-3x"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
