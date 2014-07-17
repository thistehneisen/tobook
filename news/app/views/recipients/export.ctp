<div class="subscribers index"><a href="<?php echo $this->Html->url(array("action" => "index", $mail['Mail']["id"])+$this->passedArgs); ?>" class="button sbutton" style="float:left;top:3px !important;"><?php __("Back"); ?></a>
  <h2><?php __('Export View') ?> "<?php echo $mail['Mail']['subject']; ?>"</h2>
<?php
echo $form->create(
        null,
        array(
             
            'url' => array(
                'action' => 'export',$mail['Mail']["id"]
            )+$this->passedArgs
        )
);

?>
		<?php
                __("Column",true);
                
 echo $this->Form->input('column', array("label"=>__("Columns",true),'multiple'=>'checkbox',"div"=>array("class"=>"cbox")))."<div style=\"clear:boath;\" ></div><br />";

           
   echo   $form->end(__('Download',true));
          ?> </div>
