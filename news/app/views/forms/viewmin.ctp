<div style="text-align: left;">
   
    <?php
    if (isset($done) && $done == 1) {
        echo $form['Form']['thanks_text'];
    } else if (isset($done) && $done == 2) {
        echo $form['Form']['confirm_text'];
    } else {
        echo $form['Form']['description'];
        echo $this->Form->create('Form', array('action' => 'view'));
        echo $this->Form->input("id");
        echo $this->Form->input("type", array("type" => "hidden"));
        for ($index = 1; $index <= 4; $index++) {
            if (isset($form['Content']['custom' . $index . '_label']) && !empty($form['Content']['custom' . $index . '_label'])) {
                if ($form['Content']['custom' . $index . '_top'] == 1) {
                    if (empty($form['Content']['custom' . $index . '_options'])) {
                        echo $this->Form->input('custom' . $index, array("label" => $form['Content']['custom' . $index . '_label']));
                    } else {
                        echo $this->Form->input('custom' . $index, array('empty' => '', "options" => array_combine(explode(";", $form['Content']['custom' . $index . '_options']), explode(";", $form['Content']['custom' . $index . '_options'])), "label" => $form['Content']['custom' . $index . '_label']));
                    }
                }
            }
        }



        if (!empty($form['Content']['first_name_label']))
            echo $this->Form->input('first_name', array("label" => $form['Content']['first_name_label']));
        if (!empty($form['Content']['last_name_label']))
            echo $this->Form->input('last_name', array("label" => $form['Content']['last_name_label']));
        echo $this->Form->input('e-mail', array("label" => $form['Content']['e-mail_label']));

        for ($index = 1; $index <= 4; $index++) {
            if (isset($form['Content']['custom' . $index . '_label']) && !empty($form['Content']['custom' . $index . '_label'])) {
                if ($form['Content']['custom' . $index . '_top'] != 1) {
                    if (empty($form['Content']['custom' . $index . '_options'])) {
                        echo $this->Form->input('custom' . $index, array("label" => $form['Content']['custom' . $index . '_label']));
                    } else {
                        echo $this->Form->input('custom' . $index, array('empty' => '', "options" => array_combine(explode(";", $form['Content']['custom' . $index . '_options']), explode(";", $form['Content']['custom' . $index . '_options'])), "label" => $form['Content']['custom' . $index . '_label']));
                    }
                }
            }
        }
        if ($form['Content']['user_can_choose'] == 1) {
            echo $this->Form->input('Category', array("label" => $form['Content']['categories_label'], 'multiple' => 'checkbox', "options" => $cats, "div" => array("class" => "cbox")));
        }
 
        if (isset($form['Content']['checkbox_show']) && !empty($form['Content']['checkbox_show']))
            echo $this->Form->input('checkbox', array('multiple' => 'checkbox', "label" => "","escape"=>false, "options" => array($form['Content']['checkbox_label']), "div" => array("class" => "cbox cbox4")));
      
 

        echo $this->Form->end($form['Content']['submit_button']);
    } ?>
</div>