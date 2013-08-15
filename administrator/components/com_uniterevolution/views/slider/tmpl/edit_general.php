<?php
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


// No direct access.
defined('_JEXEC') or die;

	if($this->isNew)
		$boxTitle = JText::_('COM_UNITEREVOLUTION_NEW');
	else
		$boxTitle = JText::_('COM_UNITEREVOLUTION_SLIDER_SETTINGS');
		
?>

	<div class="width-60 fltlft">
	
	<form id="form_slider_settings" name="form_slider_settings">
			
		<?php UniteFunctionJoomlaRev::putHtmlFieldsetBox($this->form, "general", $boxTitle); ?>
		
		<?php
			echo $this->loadTemplate("slidersettings"); 
		?>
	</form>
			
		<?php if($this->isNew == false):?>
			<a href="<?php echo $this->linkEditSlides?>" id="button_edit_slides_general" class="button-primary">Edit Slides</a>

			<div class="advanced_links_wrapper">
				<a href="javascript:void(0);" id="link_show_api">Show API Functions</a>
				<a href="javascript:void(0);" id="link_show_toolbox">Show Export / Import</a>	
			</div>
			
			<a id="button_preview_slider" class="button-primary" href="javascript:void(0);">Preview Slider</a>
			
		<?php
			echo $this->loadTemplate("api"); 
			echo $this->loadTemplate("export"); 
		?>
			
		<?php endif;?>
	</div> 

	<div class="width-40 fltrt">
		<form id="form_slider_params" name="form_slider_params">
			<?php UniteFunctionJoomlaRev::putHtmlFieldsetBoxes($this->form, "params","slider_params"); ?>
		</form>
	</div>
	
	<div class="clr"></div>
	
	