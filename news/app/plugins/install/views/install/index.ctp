<div class="install index">
    <h2 style="margin-top:0;"><?php echo $title_for_layout; ?></h2>
    <?php
        $check = true;

        // tmp is writable
        if (is_writable(TMP)) {
            echo '<p class="success">' . __('Your app/tmp directory is writable.', true) . '</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your app/tmp directory is NOT writable.', true) . '</p>';
        }

        // config is writable
        if (is_writable(APP.'config')) {
            echo '<p class="success">' . __('Your app/config directory is writable.', true) . '</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your app/config directory is NOT writable.', true) . '</p>';
        }

        // php version
        if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
            echo '<p class="success">' . sprintf(__('PHP version %s > 5.2', true), phpversion()) . '</p>';
        } else {
            $check = false;
            echo '<p class="error">' . sprintf(__('PHP version %s < 5.2', true), phpversion()) . '</p>';
        }
         // config is writable
        if (is_writable(APP.'config'.DS."core.php")) {
            echo '<p class="success">' . __('Your app/webroot/core.php is writable.', true) . '</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your app/webroot/core.php is NOT writable.', true) . '</p>';
        }

                 // config is writable
        if (is_writable(APP.'config'.DS."database.php")||!file_exists(APP.'config'.DS."database.php")) {
            echo '<p class="success">' . __('Your app/webroot/database.php is writable.', true) . '</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your app/webroot/database.php is NOT writable.', true) . '</p>';
        }

        if (is_writable(APP.'webroot'.DS."csv")) {
            echo '<p class="success">' . __('Your app/'.'webroot'.DS.'csv is writable.', true) . '</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your app/'.'webroot'.DS.'csv is NOT writable.', true) . '</p>';
        }

              if (is_writable(APP.'webroot'.DS."uploads")) {
            echo '<p class="success">' . __('Your app/'.'webroot'.DS.'uploads is writable.', true) . '</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your app/'.'webroot'.DS.'uploads is NOT writable.', true) . '</p>';
        }


        if ($check) {
            echo '<p>' . $html->link('Click here to begin installation', array('action' => 'database'),array("class"=>"button")) . '</p>';
        } else {
            echo '<p>' . __('Installation cannot continue as minimum requirements are not met.', true) . '</p>';
        }
    ?>
</div>
