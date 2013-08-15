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

// Load submenu template, using element id 'submenu' as needed by behavior.switcher

$sliderID = $this->item->id;

try{
	
?>

<div id="slider_view" class="sliderView unitefields">

<?php echo $this->loadTemplate('general'); ?>
	
<?php
	HelperUniteRev::includeView("slider/tmpl/dialog_preview_slider.php");
	HelperUniteRev::includeView("sliders/tmpl/footer.php");	 
?>
		

	<script type="text/javascript">
		var g_sliderID = "<?php echo $this->sliderID?>";
		var g_viewSliderPattern = "<?php echo $this->viewSliderPattern?>"; 
		var g_urlViewSliders = "<?php echo UniteFunctionJoomlaRev::getViewUrl(GlobalsUniteRev::VIEW_SLIDERS)?>";
		
		jQuery("document").ready(function(){
			<?php if(!empty($this->messageOnStart)): ?>
				UniteAdminRev.showSystemMessage("<?php echo $this->messageOnStart?>");
			<?php endif?>
			
			UniteRevSlider.initSliderView();
		});
		
	</script>

</div>	

<div class="clr"></div>



<?php
		}catch(Exception $e){
			//show system error
			$message = $e->getMessage();
			$message = str_replace("\\", "/", $message);
			$message = stripslashes($message);
			
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					UniteRevSlider.showSliderViewError('<?php echo $message?>');
				});
			</script>
			<?php 
		} 

?>

