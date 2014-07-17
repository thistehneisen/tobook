<div>

    <?php echo $this->Html->link($this->Html->image("icons/eraser--arrow.png", array("alt" => __('conf', true))) . __('Remove Failed Subscribers', true), "#", array("onclick" => "job('" . $this->Html->url(array("action" => "runjob")) . "');", "escape" => false, 'class' => 'button runbutton', 'style' => 'float: right; margin-top: -5px; margin-right: 0px;')); ?>
    <?php echo $this->Html->link($this->Html->image("icons/eraser--arrow.png", array("alt" => __('conf', true))) . __('Remove Inactive Subscribers', true), "#", array("onclick" => "job('" . $this->Html->url(array("action" => "removeinactive")) . "');", "escape" => false, 'class' => 'button runbutton', 'style' => 'float: right; margin-top: -5px; margin-right: 12px;')); ?>
            <h2><?php __('Unsubscribe invalide subscribers'); ?></h2><div id="runn" style="display: none;">
        <h4><?php __('Live ticker'); ?> <img src="<?php echo $this->Html->url("/"); ?>img/indi.gif" id="worker"/></h4>
        <div id="jobs"></div></div>
    <hr class="space" />
</div><script type="text/javascript">
    $(document).ready(function(){
        $("#worker")
        .ajaxStart(function() {$(this).show();})
        .ajaxStop(function() {$(this).hide();}).hide();

        }

    );
 function job(path){
            $("#runn").fadeIn();
            $(".runbutton").fadeOut();
            $.ajax({ url: path, success: function(data){

                    var vals = data.split("-");
                    if(data.indexOf("<") == -1){
                        $("#jobs").prepend($("<div class=\"message success\">"+vals[0]+"</div>").hide().fadeIn());
                         vals.shift();
                var uri=vals.join('-');
                        if(uri!= null) {
                            job(uri);
                        }
                        if($("#jobs div").size()>6){
                            $("#jobs div:last").remove();
                        }
                    }
                }});
 }
</script>