<?php 
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access'); ?>

<?php 

	$numSliders = count($this->arrSliders);

	if($numSliders == 0){	//error output
		?>
			<h2>Please add some slider before operating slides</h2>
		<?php 
	}else
		echo $this->loadTemplate("slide");
	
	
	HelperUniteRev::includeView("slider/tmpl/dialog_preview_slider.php");
	HelperUniteRev::includeView("sliders/tmpl/footer.php");	 
		
?>

	<script type="text/javascript">
		var g_sliderID = "<?php echo $this->sliderID?>";
		var g_urlSliders = "<?php echo HelperUniteRev::getViewUrl_Sliders();?>";
		
		jQuery("document").ready(function(){
			
			UniteRevSlider.initItemsView();
		});
		
	</script>
