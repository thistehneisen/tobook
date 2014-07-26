<h2><?php __("Import Subscribers from CSV"); ?></h2>
<?php
echo $form->create(
        null, array(
    'type' => 'file',
    'url' => array(
        'action' => 'import2', $file
    )
        )
);
$heads = array();
$col = 0;
__("Delimiter", true);
__("Enclosure", true);

echo $form->input("delimiter");
echo $form->input("enclosure");
echo $form->input("form", array("label" => __("Subscription Form", true)));
echo $form->input("existing", array("options" =>
    array("ignore" => __("Ignore", true), "update" => __("Update categories", true), "overwrite" => __("Overwrite categories", true), "delete" => __("Delete", true), "unsubscribe" => __("Unsubscribe", true)), "label" => __('Existing subscribers', true))
);
echo $form->input("resub", array("label" => __("Resubscribe", true), "type" => "checkbox"));
?>
<?php
echo $this->Form->input('Category', array("label" => __("Add to this category", true), 'multiple' => 'checkbox', "div" => array("class" => "cbox"))) . "<div style=\"clear:boath;\" ></div><br />";
echo "<h3>" . __("Data", true) . "</h3>";
echo $form->input("rem_header", array("label" => __("Remove first row", true), "type" => "checkbox"));
foreach ($data[0] as $h) {
    $cols = array("first_name" => __("First Name", true), "last_name" => __("Last Name", true), "mail_adresse" => __("Mail Address", true), "notes" => __("Notes", true));
    if (Configure::read('Settings.custom1_show') == "1") {
        $cols["custom1"] = Configure::read('Settings.custom1_label');
    }
    if (Configure::read('Settings.custom2_show') == "1") {
        $cols["custom2"] = Configure::read('Settings.custom2_label');
    }
    if (Configure::read('Settings.custom3_show') == "1") {
        $cols["custom3"] = Configure::read('Settings.custom3_label');
    }
    if (Configure::read('Settings.custom4_show') == "1") {
        $cols["custom4"] = Configure::read('Settings.custom4_label');
    }
    $heads[] = $form->select(
                    'Import.col' . $col, $cols
    );

    $col++;
}
?><div style="overflow:auto;"><?php
echo $html->tag(
        'table', $html->tableHeaders(
                $heads
        ) . $html->tableCells(
                $data
        ));
?></div><?php
$form->end(__('Upload',true));
?><div class="submit"><input name="data[clicked]" type="submit" value="<?php echo __("Refresh"); ?>"><input name="data[clicked]" type="submit" value="<?php echo $updatebutton ?>"></div>
