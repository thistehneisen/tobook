<div class="menus form">
    <a style="float:right;" class="button" href="<?php echo $this->Html->url(array("action" => "clean")); ?>"><?php __("Clean Cache"); ?></a>
    <h2><?php __('Settings'); ?></h2>
    <?php echo $this->Form->create('Settings'); ?>

	<h4><?php __("Application"); ?></h4>
	<?php echo $this->Form->input('language', array("options" => $langs, "label" => __("Language", true)));
	echo $this->Form->input('timezone', array("options" => $timezones, "label" => __("Timezone", true)));
    echo $this->Form->input('nice_image_url', array("type" => "checkbox", "label" => __("Nice Image Url", true)));
	?>
	<hr /><h4><?php __("Parallel Delivery"); ?></h4>
	<?php echo $this->Form->input('parallel_jobs', array("type" => "checkbox", "label" => __("Enable", true)));
	echo $this->Form->input('parallel_jobs_count', array( "label" => __("Parallel Jobs", true)));
	?>

    <hr /><h4><?php __("Custom Fields"); ?></h4>
    <?php
    echo $this->Form->input('custom1_label', array("label" => __("Custom 1 Label", true)));
    echo $this->Form->input('custom1_show', array("type" => "checkbox", "label" => __("Show Custom 1", true)));
    echo $this->Form->input('custom2_label', array("label" => __("Custom 1 Label", true)));
    echo $this->Form->input('custom2_show', array("type" => "checkbox", "label" => __("Show Custom 2", true)));
    echo $this->Form->input('custom3_label', array("label" => __("Custom 1 Label", true)));
    echo $this->Form->input('custom3_show', array("type" => "checkbox", "label" => __("Show Custom 3", true)));
    echo $this->Form->input('custom4_label', array("label" => __("Custom 1 Label", true)));
    echo $this->Form->input('custom4_show', array("type" => "checkbox", "label" => __("Show Custom 4", true)));
    ?><hr /><h4><?php __("Dashboard"); ?></h4>
    <?php
    echo $this->Form->input('api_show', array("type" => "checkbox", "label" => __("Show API to User", true)));
    echo $this->Form->input('cron_show', array("type" => "checkbox", "label" => __("Show Cronjob to User", true)));
    ?><hr /><h4><?php __("Rss Feed"); ?></h4>
    <?php
    echo $this->Form->input('norss', array("type" => "checkbox","label" => __("Disable RSS", true)));
    echo $this->Form->input('ctitle', array("label" => __("Channel Title", true)));
    echo $this->Form->input('cdescription', array("type" => "textarea", "label" => __("Channel Description", true)));
    echo $this->Form->input('clink', array("label" => __("Channel Link", true)));
    echo $this->Form->input('author', array("label" => __("Author", true)));
    echo $this->Form->input('rssitems', array("label" => __("Rss Items", true)));
      echo $this->Form->input('stoppednl', array("type" => "checkbox","label" => __("Show Stopped Newsletters", true)));
    ?>

    <?php echo $this->Form->end(__('Save', true)); ?>
</div>
