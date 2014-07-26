<h2><?php __('Run Import Task'); ?></h2>
<h4><?php __('Live ticker'); ?> <img src="<?php echo $this->Html->url("/"); ?>img/indi.gif" id="worker"/></h4>
<div id="jobs"></div>
<script type="text/javascript">
$(document).ready(function(){
$("#worker")
        .ajaxStart(function() {$(this).show();})
        .ajaxStop(function() {$(this).hide();}).hide();
           function job(path){
        $.ajax({ url: path, success: function(data){

                var vals = data.split ("-")
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
    job("<?php echo $this->Html->url($path); ?>");
});

</script>
