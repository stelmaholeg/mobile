<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access.
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

	<div id="edit_slide_wrapper" class="edit_slide_wrapper">
		<div class="width-100 fltlft">
			<?php
				echo $this->loadTemplate("inside"); 
			?>
		</div>	<!-- width_100 -->
	</div> <!-- slide wrapper -->

	<div id="dialog_preview" class="dialog_preview" title="Preview Slide" style="display:none;">
		<iframe id="frame_preview" name="frame_preview" style="<?php echo $this->styleIframe?>"></iframe>
	</div>
	
	<form id="form_preview" name="form_preview" action="" target="frame_preview" method="post">
		<input type="hidden" name="client_action" value="preview_slide">
		<input type="hidden" name="data" value="" id="preview_slide_data">
	</form>


	<script type="text/javascript">
		var g_urlViewItems = "<?php echo $this->urlViewItems?>";
		var g_viewItemPattern = "<?php echo $this->urlViewItemPattern?>";
		var g_viewItemNew = "<?php echo $this->urlViewItemNew?>";
		var g_sliderID = "<?php echo $this->sliderID?>";
		var g_slideID = "<?php echo $this->slideID?>";
		
		jQuery(document).ready(function(){
			UniteRevSlider.initItemView(g_slideID,g_sliderID);
		});
		
	</script>
	

<div class="clr"></div>

<?php 
	HelperUniteRev::includeView("sliders/tmpl/footer.php");
?>	 

