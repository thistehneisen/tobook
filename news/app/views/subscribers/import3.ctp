<h2><?php __("Import Subscribers from CSV"); ?><img src="<?php echo $this->Html->url("/"); ?>img/indi.gif" id="worker"/></h2>
<dl style="margin-top:12px;">
         <dt style="color:green"><?php __("Lines Loaded"); ?></dt>
        <dd style="color:green">
            <span id="loaded">0</span> / <?php echo $cc; ?> (<span id="prec">0</span>%)            &nbsp;
        </dd>
     

    </dl>
<div style="float: left; width: 920px; margin-right: 0px; margin-bottom: 24px; margin-top: 0px;" class="progress-container"><div id="indicator"  style="background:#69C620; width: 0%"></div>

</div>
<script type="text/javascript">
    var total=<?php echo $cc; ?>;
    $(document).ready(function(){
        $("#worker")
        .ajaxStart(function() {$(this).show();})
        .ajaxStop(function() {$(this).hide();}).hide();
        job(0,50); 
    }
             
);
         
    function job(start,size){
        $.ajax({ url: "<?php echo $this->Html->url("/subscribers/import3/" . $file) ?>/"+start+"/"+(start+size),
            data:<?php echo $pass; ?>,
            type:"POST",
            success: function(data){
                
                if(start+size<total){
                    $("#loaded").text((start+size));
                    $("#prec").text(Math.round(((start+size)/total)*1000)/10);
                    $("#indicator").animate({

                    width: ((start+size)/total)*100+'%' 
  
                }, 1000, function() {
                    // Animation complete.
                });
                    job(start+size,size); 
                }else{
                $("#loaded").text((total));
                $("#prec").text("100");
                $("#indicator").animate({

                    width: '100%' 
  
                }, 1000, function() {
                    // Animation complete.
                });
                window.location = "<?php echo $this->Html->url("/subscribers/import4/" . $file,true) ?>/";

                }
            }});
        
      
    }
</script>