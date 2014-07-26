<div class="install form">
    <h2 style="margin-top:0;"><?php echo $title_for_layout; ?></h2>
    <?php
        echo $form->create('Install', array('url' => array('plugin' => 'install', 'controller' => 'install', 'action' => 'database')));
        echo $form->input('Install.driver', array(
            'label' => 'Driver',
            'value' => 'mysql',
            'empty' => false,
            'options' => array(
                'mysql' => 'mysql'
            
            ),
        ));
        //echo $form->input('Install.driver', array('label' => 'Driver', 'value' => 'mysql'));
        echo $form->input('Install.host', array('label' => 'Host', 'value' => 'localhost'));
        echo $form->input('Install.login', array('label' => 'Username', 'value' => 'root'));
        echo $form->input('Install.password', array('label' => 'Password'));
        echo $form->input('Install.database', array('label' => 'Database Name', 'value' => 'newsletter'));
        echo $form->input('Install.port', array('label' => 'Port (leave blank if unknown)'));
        echo $form->end('Save');
    ?>
</div>
