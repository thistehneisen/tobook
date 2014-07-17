<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<h2><?php __("Upload missing images"); ?></h2>
<?php

echo $form->create(
        null,
        array(
            'type' => 'file',
            'url' => array(
                'action' => 'images',$id
            )
        )
);
$i = 0;
foreach ($img as $im) {
    $i++;
?>
<div><?php    echo $form->label(
            'Image.image' . $i,
            $im,array("style"=>"width:300px;")
    );
    echo $form->file(
            'Image.image' . $i
    );
       echo $form->hidden(
            'Image.path' . $i,array("value"=>$im)
    );
    ?>
</div><?php }
echo $form->end('Upload');
?>
