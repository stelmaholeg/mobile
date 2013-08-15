<?php 
/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


defined('_JEXEC') or die('Restricted access'); ?>

<?php 

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

$user		= JFactory::getUser();
$userId		= $user->get('id');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$canOrder	= true; //$user->authorise('core.edit.state', 'com_contact.category');
$saveOrder	= $listOrder == 'a.ordering';

$table = new UniteAdminTableRev($this->state);
$table->addFilter(UniteAdminTableRev::FILTER_TYPE_PUBLISHED); 


?>

<form action="<?php echo JRoute::_('index.php?option=com_uniterevolution&view=sliders'); ?>" method="post" name="adminForm" id="adminForm">

	<?php
		$table->putFilterBar(); 
	?>
		
	<div class="clr"> </div>
	
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
				<th>
					<?php echo JHtml::_('grid.sort',  'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th width="8%">
				</th>								
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.published', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php  echo JText::_('COM_UNITEREVOLUTION_PREVIEW') ?>
				</th>
				<th width="1%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php 
		$n = count($this->items);
		foreach ($this->items as $i => $slider) :
			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= true; 
			$canEdit	= true; 
			$canCheckin	= true; 
			$canEditOwn	= true; 
			$canChange	= true;
			
			$sliderID = $slider->id;
			
			$urlSliderSettings = HelperUniteRev::getViewUrl_Slider($sliderID);
			$urlEditSlides = HelperUniteRev::getViewUrl_Items($sliderID);
			$title = $this->escape($slider->title);
			
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $sliderID); ?>
				</td>
				<td height="30">
					<a href="<?php echo $urlSliderSettings ?>"><?php echo $title ?></a>
				</td>
				<td class="center">
					<?php echo JHtml::link($urlEditSlides, JText::_('COM_UNITEREVOLUTION_EDIT_SLIDES'))?>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $slider->published, $i, 'sliders.', true, 'cb'	); ?>
				</td>
				<td class="preview">
					<a id="button_preview_<?php echo $sliderID?>" href="javascript:void(0)" class="button_slider_preview" title="<?php echo JText::_("COM_UNITEREVOLUTION_PREVIEW")." ".$title?>" data-title="<?php echo $title?>"></a>
				</td>
				<td align="center">
					<?php echo $slider->id; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

	<script type="text/javascript">
		
		UniteAdminRev.hideSystemMessageDelay();
		
		UniteRevSlider.initSlidersView();
		
	</script>

<?php
	HelperUniteRev::includeView("slider/tmpl/dialog_preview_slider.php");
	HelperUniteRev::includeView("sliders/tmpl/footer.php");	 
?>
