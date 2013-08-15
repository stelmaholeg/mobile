<?php 
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


// No direct access.
defined('_JEXEC') or die;

	JHTML::_('behavior.tooltip');
	JHTML::_('behavior.modal');
	
?>

<form action="form_slides" method="post" name="adminForm" id="adminForm">

	<input type="hidden" name="sliderid" value="<?php echo $this->sliderID?>">
	
	<fieldset id="filter-bar">
	
		<div class="filter-select fltlft">
			
			<?php if(!empty($this->linkSliderSettings)):?>
				<a href="<?php echo $this->linkSliderSettings?>" id="link_slider_settings" class="button-primary"><?php echo JText::_('COM_UNITEREVOLUTION_EDIT_SLIDER_SETTINGS')?></a>
				
				<a href="javascript:void(0)" id="button_preview_slider_fromitems" class="button-primary mleft_10"><?php echo JText::_('COM_UNITEREVOLUTION_PREVIEW_SLIDER')?></a>
			<?php endif?>
			<span id="order_status_text" class="text_saving_order" style="display:none;"><?php echo JText::_("COM_UNITEREVOLUTION_SAVING_ORDER")?></span>
		</div>
		
		
	</fieldset>
	
	<div class="clr"> </div>
	
		<div id="loader_reloading" class="text_reloading_page" style="display:none">
			<?php echo JText::_("COM_UNITEREVOLUTION_RELOADING_PAGE")?>...
		</div>
		
		<div id="items_list_wrapper" class="items_list_wrapper">			
			<ul id="list_items" class="list_items">
			
			<?php 
			$n = count($this->items);
			
			foreach ($this->items as $i => $item):
				$numItem = $i+1;
				$html = $this->getSlideHtml($item,$numItem);
				echo $html;
				
			endforeach;
			?>
		</ul>
			
	</div>
		
	
</form>
