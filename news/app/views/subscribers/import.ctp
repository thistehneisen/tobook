<h2><?php __("Import Subscribers from CSV"); ?></h2>
<?php

echo $form->create(
        null,
        array(
            'type' => 'file',
            'url' => array(
                'action' => 'import'
            )
        )
);
__('File',true);
    echo $form->label(
            'Import.file'
         
    );
    echo $form->file(
            'Import.file'
    );
    echo $form->end(__('Upload',true));
?>
