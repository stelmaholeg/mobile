<?php
/**
 *Blank module
 */
// no direct access
defined('_JEXEC') or die;
// Get needed params
/*$mod_url = JURI::root();
$app = JFactory::getApplication();
$templatenames = $app->getTemplate();
$use_default_styles = $params->get('template_style');*/
?>

<div class = "advertising">
	<div class = "advertising_blocks">
		<a href="<?php echo $params->get('link_1'); ?>" class="first-block-adver" title = "<?php echo $params->get('title_1'); ?>"><img src="<?php echo $params->get('img_1'); ?>" alt=""/></a>
	</div>
	<div class = "advertising_blocks">
		<a href="<?php echo $params->get('link_2'); ?>" title="<?php echo $params->get('title_2'); ?>"><img src="<?php echo $params->get('img_2');?>" alt=""/></a>
	</div>
	<div class = "advertising_blocks">
		<a href="<?php echo $params->get('link_3'); ?>" title="<?php echo $params->get('title_3'); ?>"><img src="<?php echo $params->get('img_3');?>" alt=""/></a>
	</div>
	<div class = "advertising_blocks">
		<a href="<?php echo $params->get('link_4'); ?>" title="<?php echo $params->get('title_4'); ?>"><img src="<?php echo $params->get('img_4');?>" alt=""/></a>
	</div>
	<div style = "clear:both;"></div>
</div>