<?php

/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

	// No direct access.
	defined('_JEXEC') or die;
	
	 $fieldSetOptional = $this->form->getFieldset('optional');
	 $styleImagePreview = "";
?>

	<div class="slide_wrapper_inside">
	
<form name="form_slide_params" id="form_slide_params" class="form-validate">
	
			<div class="slide_settings_wrapper unitefields">
			
			<a id="link_hide_options" href="javascript:void(0)">Hide Slide Options</a>
			
			<fieldset class="adminform" id="fieldset_slide">
				<legend><?php echo empty($this->item->id) ? JText::_('COM_UNITEREVOLUTION_NEW') : JText::sprintf('COM_UNITEREVOLUTION_EDIT', $this->item->id); ?></legend>
					<div id="fieldset_slide_inside">
						<table style="width:100%">
							<tr>
								<td valign="top">
									<ul class="adminformlist" id="slide_list1">
										<li>
											<div>
												<?php $this->putField("title") ?>
											</div>
										</li>
										<li>
											<?php $this->putField("published") ?>
										</li>
										<li>
											<div class="sap_vert"></div>
										</li>
										<?php $this->putOptionalFieldList("slide_transition");?>
										<?php $this->putOptionalFieldList("slot_amount");?>
										<?php $this->putOptionalFieldList("transition_rotation");?>	
									</ul>
								</td>
								<td>
									<ul class="adminformlist" id="slide_list2">
										<?php $this->putOptionalFieldList("transition_duration");?>
										<?php $this->putOptionalFieldList("delay");?>
										<?php $this->putOptionalFieldList("enable_link");?>
										<?php $this->putOptionalFieldList("link_type");?>
										<?php $this->putOptionalFieldList("link");?>
										<?php $this->putOptionalFieldList("link_open_in");?>
										
										<?php $this->putSlideLinkField(); ?>
										<?php $this->putOptionalFieldList("link_pos");?>
										
										<?php $this->putOptionalFieldList("enable_video");?>
										<?php $this->putOptionalFieldList("video_id");?>
										<?php $this->putOptionalFieldList("video_autoplay");?>
										<?php $this->putOptionalFieldList("slide_thumb");?>
									</ul>
								</td>							
							</tr>						
						</table>
					</div>
			</fieldset>
			</div>
			
	<input type="hidden" name="task" value="" />
</form>
			
			<div class="clear"></div>
			
			<?php 
				echo $this->loadTemplate("layers");
			?>
			
		</div>	<!-- slide wrapper inside -->
	