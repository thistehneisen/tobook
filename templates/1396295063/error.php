<?php
/**
* @subpackage  ol_anteez Template
*/

defined('_JEXEC') or die;
if (!isset($this->error)) {
$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
$this->debug = false;
}
//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/error.css" type="text/css" />
<?php /* if ($this->direction == 'rtl') : */ ?>
<!-- <link rel="stylesheet" href="<?php /* echo $this->baseurl; */ ?>/templates/<?php  /* echo $this->template; */?>/css/error_rtl.css" type="text/css" /> -->
<?php /* endif; */ ?>
</head>
<body>
<div id="error-container">
<div id="error-content">
<h1 id="error-type"><?php echo $this->error->getCode(); ?></h1>
<h2 id="error-message"><?php echo $this->error->getMessage(); ?></h2>


<div id="content-left">
<p><strong><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></strong></p>
<ol>
<li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
<li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
<li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
<li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
<li><?php echo JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'); ?></li>
<li><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></li>
</ol>
</div>

<div id="content-right">
<p><strong><?php echo JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES'); ?></strong></p>

<ul>
<li><a href="<?php echo $this->baseurl; ?>/index.php" title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></li>
</ul>
</div>


<div id="error-info">
<p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?>.</p>
<div id="techinfo">
<p><?php echo $this->error->getMessage(); ?></p>
<p>
<?php if ($this->debug) :
echo $this->renderBacktrace();
endif; ?>
</p>
</div>
</div>


</div>
</div>
</body>
</html>
