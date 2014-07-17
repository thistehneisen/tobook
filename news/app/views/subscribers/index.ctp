<div class="subscribers index">
    <?php
    ?>
    <?php echo $this->Html->link($this->Html->image("icons/xfn--plus.png", array("alt" => __('Add', true))) . __('New Subscriber', true), array('action' => 'add', $cat), array("escape" => false, 'class' => 'button', 'style' => 'float: right; margin-top: -5px;margin-right:0px;')); ?>    <h2>
        <?php
        if ($cat == "search") {
            __('Subscribers');
            echo " " . __("Search", true);
        } else {
            __('Subscribers');
            echo " (" . $categories_list[$cat] . ")";
        }
        ?></h2>
    <hr />

    <?php
    $cla = "bglow";
    if ($cat != "search") {
        $cla = "";
    }
    echo $this->Html->link($this->Html->image("icons/magnifier.png", array("alt" => __('cat', true))) . __("Search", true), "#", array("escape" => false, "onclick" => "$('#search').slideToggle();return false;", 'class' => 'button ' . $cla, 'style' => 'float: left; margin-top: -5px;margin-bottom: 12px;'));

    echo $this->Html->link($this->Html->image("icons/xfn-colleague.png", array("alt" => __('cat', true))) . __("Uncategorized", true).$uncatd, array('action' => 'index'), array("escape" => false, 'class' => 'button', 'style' => 'float: left; margin-top: -5px;margin-bottom: 12px;'));
    foreach ($categories as $value) {


        echo $this->Html->link($this->Html->image("icons/xfn-colleague.png", array("alt" => __('cat', true))) . $value["Category"]["fullname"], array('action' => 'index', $value["Category"]["id"]), array("escape" => false, 'class' => 'button', 'style' => 'float: left; margin-top: -5px;margin-bottom: 12px;'));
    }
    ?>  <div style="clear:both">&nbsp;</div>
    <div id="search" style="display:none;" >
        <?php echo $form->create('Subscriber', array('action' => 'search')); ?>
        <fieldset style="margin-top: 0px;">
            <legend><?php __('Subscriber Search'); ?></legend>
            <?php
            echo $form->input('Search.first_name', array('after' => __(' wildcard is *', true)));
            echo $form->input('Search.last_name', array('after' => __(' wildcard is *', true)));
            echo $form->input('Search.mail_adresse', array("label" => __("Mail Addresse", true), 'after' => __(' wildcard is *', true)));
			 for ($i = 1; $i <= 4; $i++){
				 if (Configure::read('Settings.custom3_show') == "1") {

					 echo $form->input('Search.custom' . $i  , array("label" => Configure::read('Settings.custom'.$i.'_label'), 'after' => __(' wildcard is *', true)));
				 }

			 }
            echo $form->input('Search.created', array("label" => __("Subscribed", true), 'after' => __(' eg: >= 1 week ago', true)));
            echo $form->input('Search.deleted', array("label" => __("Status", true), "options"=>array(""=>__("Subscribed/Unsubscribed",true),"0"=>__("Only Subscribed",true),"1"=>__("Only Unsubscribed",true))));
             
            echo $form->submit('Search');
            ?>
        </fieldset>
        <?php echo $form->end(); ?></div>

    <table cellpadding="0" cellspacing="0">
        <tr> <th  style="width:15px;"><input type="checkbox"  name="all" id="all" /></th>
            <th  style="width:35px;"><?php echo $this->Paginator->sort('id'); ?></th>
            <th ><?php echo $this->Paginator->sort('first_name'); ?></th>
            <th ><?php echo $this->Paginator->sort('last_name'); ?></th>
            <th ><?php echo $this->Paginator->sort(__("Mail Addresse", true), 'mail_adresse'); ?></th>
	        <?php

	        for ($i = 1; $i <= 4; $i++) {
		        if (Configure::read('Settings.custom' . $i . '_show') == "1"&& isset($this->data['Search']['custom' . $i])) {
	?>
			        <th><?php echo $this->Paginator->sort(Configure::read('Settings.custom' . $i . '_label'), 'custom' . $i); ?></th><?php

		        }
	        }
?>
            <th ><?php echo $this->Paginator->sort(__("Subscribed", true), 'created'); ?></th>

            <th class="actions" style="width:60px;"><?php __('Actions'); ?></th>
        </tr>
        <tr class="select_all">
            <td ><input type="checkbox"  value="all" name="toall" /></td> 
            <td colspan="6" class="batch"><?php echo str_replace("%s", $this->Paginator->counter("%count%"),__('Select all %s records',true)); ?></td> 
        </tr>
        <?php
        $i = 0;
        foreach ($subscribers as $subscriber):
            $class = null;
            if ($subscriber['Subscriber']['deleted'] == 1) {
                $class = ' class="rowdel"';
            }
            if ($i++ % 2 == 1) {
                $class = ' class="altrow"';
                if ($subscriber['Subscriber']['deleted'] == 1) {
                    $class = ' class="altrowdel"';
                }
            }
            ?>
            <tr<?php echo $class; ?>>
                <td><input type="checkbox"  value="<?php echo $subscriber['Subscriber']['id']; ?>" name="del[]" /></td>
                <td class="batch"><?php echo $subscriber['Subscriber']['id']; ?>&nbsp;</td>
                <td class="batch"><?php echo $subscriber['Subscriber']['first_name']; ?>&nbsp;</td>
                <td class="batch"><?php echo $subscriber['Subscriber']['last_name']; ?>&nbsp;</td>
                <td class="batch"><?php echo $subscriber['Subscriber']['mail_adresse']; ?>&nbsp;</td>
	            <?php

	            for ($o = 1; $o <= 4; $o++) {
		            if (Configure::read('Settings.custom' . $o . '_show') == "1" && isset($this->data['Search']['custom' . $o])) {
			            ?>
			            <td class="batch"><?php echo $subscriber['Subscriber']['custom'.$o]; ?>&nbsp;</td><?php
		            }
	            }
	            ?>
                <td style="width:160px" class="batch"><?php echo toUTF8(strftime("%e %B %Y, %H:%M", strtotime($subscriber['Subscriber']['created']))); ?>&nbsp;</td>

                <td class="batch">
                    <?php echo $this->Html->link($this->Html->image("icons/information-white.png", array("alt" => __('View', true))), array('action' => 'view', $subscriber['Subscriber']['id']), array("escape" => false, "title" => __('View', true) . "  &quot;" . htmlspecialchars($subscriber['Subscriber']['mail_adresse']) . "&quot;")); ?>
                    <?php echo $this->Html->link($this->Html->image("icons/xfn--pencil.png", array("alt" => __('Edit', true))), array('action' => 'edit', $subscriber['Subscriber']['id']), array("escape" => false, "title" => __('Edit', true) . "  &quot;" . htmlspecialchars($subscriber['Subscriber']['mail_adresse']) . "&quot;")); ?>
                    <?php echo $this->Html->link($this->Html->image("icons/xfn--minus.png", array("alt" => __('Delete', true))), array('action' => 'delete', $subscriber['Subscriber']['id'], $cat), array("escape" => false, "title" => __('Delete', true) . "  &quot;" . htmlspecialchars($subscriber['Subscriber']['mail_adresse']) . "&quot;"), sprintf(__('Are you sure you want to delete # %s?', true), $subscriber['Subscriber']['id'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
    echo $form->create(
            null, array(
        'url' => array(
            'action' => 'batchAction'
        )
            )
    );
echo $form->hidden('Search.query');
    echo $this->Form->hidden("data");
    echo $this->Form->hidden("cat", array("value" => $cat));
    $actionss = array("delete" => __("Delete", true), "unsub" => __("Unsubscribe", true), "sub" => __("Subscribe", true));
    if ($cat > 0) {
        $actionss["rem"] = __("Remove from this Categorie", true);

        $actionss["move"] = __("Move to", true);
    }
    $actionss["remf"] = __("Remove from", true);
    $actionss["add"] = __("Add to", true);
    $actionss["over"] = __("Overwrite category with", true);
    echo $this->Form->select("action", $actionss, null, array("style" => "margin-right:12px"));
    
    echo $this->Form->select("category", $categories_list, null, array("style" => "margin-right:12px"));
    ?><button type="submit" ><?php __("Run Action"); ?></button>  <?php
    echo $form->end();
    ?>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
        ));
        ?>            </p>

    <div class="paging">
        <?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled')); ?>
                                	 | 	<?php echo $this->Paginator->numbers(); ?>
        |
        <?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
    </div>

</div>
<script type="text/javascript">
    function updateBatch(){
        $("#SubscriberData").val("");
        $("td input[type=checkbox]:checked").each(function(){
            if(this.name!="all"){
                $("#SubscriberData").val($("#SubscriberData").val()+$(this).val()+";");
            }
        });
        if($(".select_all td input")[0].checked){
            $("#SubscriberData").val("toall");
        }
       
       
    }   
    function updateS(){
        if($("#all")[0].checked){
            $(".select_all").show();
            $(".select_all td input")[0].checked=false;
        }else{
            $(".select_all").hide();
            $(".select_all td input")[0].checked=false;
        }
    }
    $().ready(function() {
        updateS();
        $("tr").hover( function(e) {
            $(this).addClass("rowHighlight");
        },
        function(e) {
            $(this).removeClass("rowHighlight");
        });
        $("td input[type=checkbox]").change(function(){
            if(this.name!="all"&&this.name!="toall"){
                $("#all")[0].checked=false;
                updateS();
            }
        });
        $(".batch").click(function(eo){
           
            if($(this).parent().find("input")[0].name!="all"){
                $(this).parent().find("input")[0].checked=!$(this).parent().find("input")[0].checked; 
                if($(this).parent().find("input")[0].name!="toall"){
                    $("#all")[0].checked=false;
                    updateS();
                }
            }
        });
        $("#all").change( function() {
            $("td input[type=checkbox]").each(function(){
                if(this.name!="all"&&this.name!="toall"){
                    this.checked=$("#all")[0].checked;
                    
                }
            });
            updateS();
        });
 
        $("form").submit(function(){
            updateBatch();
        });
    });
     
</script>