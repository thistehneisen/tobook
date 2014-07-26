<h2><?php __("Import Template"); ?></h2>
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
    echo $form->label(
            'Import.file'
         
    );
    echo $form->file(
            'Import.file'
    );
    echo $form->end('Upload');
?>
