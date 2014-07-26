<?php
    echo $javascript->codeBlock(
        "function selectURL(url) {
            if (url == '') return false;

           
            if(window.top.opener.jq!=null){
            window.top.opener.browserField.val('/uploads/' + url);
            }else{
             url = '".Helper::url('/uploads/')."' + url;
            field = window.top.opener.browserWin.document.forms[0].elements[window.top.opener.browserField];
            field.value = url;
            if (field.onchange != null) field.onchange();
            }
            window.top.close();
            window.top.opener.browserWin.focus();
        }"
    );
?>

<?php
    echo $form->create(
        null,
        array(
            'type' => 'file',
            'url' => array(
                'action' => 'upload'
            )
        )
    );
    echo $form->label(
        'Image.image',
        __('Upload image',true)
    );
    echo $form->file(
        'Image.image'
    );
    ?><h5 style="margin-top: 5px; margin-bottom: 5px; padding-left: 150px;">or</h5><?php
        echo $form->input(
        'Image.url',
      array("label"=>__(  'Image Url',true))
    );
    echo $form->end(__('Upload',true));
?>

<?php if(isset($images[0])) {
    $tableCells = array();

    foreach($images As $the_image) {
    ?>
<div style="float: left; padding: 10px; width: 150px; height: 160px;">
 <?php echo $html->link(
                '<img src="'.$html->url("/").'thumb.php?src='.'/uploads/'.$the_image['basename'].'" />',
                '#',
                array('escape'=>false,"style"=>"margin-left:25px;margin-right:25px;",
                    'onclick' => 'selectURL(\''.$the_image['basename'].'\');'
                )); ?><br />
  <p style="text-align:center"><?php echo $the_image['basename']."<br />".$number->toReadableSize($the_image['size']); ?></p>
</div>

<?php        $tableCells[] = array(
           
            
            $number->toReadableSize($the_image['size']),
            date('m/d/Y H:i', $the_image['last_changed'])
        );
    }

} ?> 