<h2>Export Subscribers to CSV</h2>
<?php
echo $form->create(
        null,
        array(
             
            'url' => array(
                'action' => 'export'
            )
        )
);

?>
		<?php
                __("Column",true);
                
 echo $this->Form->input('column', array("label"=>__("Columns",true),'multiple'=>'checkbox',"div"=>array("class"=>"cbox")))."<div style=\"clear:boath;\" ></div><br />";

                echo $this->Form->input('Category', array("label"=> __("Categories",true),'multiple'=>'checkbox',"div"=>array("class"=>"cbox")))."<div style=\"clear:boath;\" ></div><br />";

   echo   $form->end(__('Download',true));
          ?> 
