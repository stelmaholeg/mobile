<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access.
defined('_JEXEC') or die;
	
?>

<!-- unite master view -->

<input type="hidden" id="field_image_dialog_choose" name="field_image_dialog_choose">

<div id="div_debug" style="display:none;"></div>

<script type="text/javascript">
	var g_jsonControls = '<?php echo $jsonControls?>';
	var g_controls = jQuery.parseJSON(g_jsonControls);
	var g_urlBase = "<?php echo GlobalsUniteRev::$urlBase?>";
	
</script>

<?php require $this->getMasterTemplatePath("video_dialog.php");?>

<?php parent::display($this->userTemplate);?>

<!-- unite master view end -->