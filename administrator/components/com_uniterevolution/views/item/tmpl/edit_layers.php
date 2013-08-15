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

			<div class="edit-layer-buttons-wrapper">			
			
				<?php $this->putOptionalField("image");?>
				<a id="button_preview_slide" class="btn btn-large margin_left10" title="Preview Slide" href="javascript:void(0)"><i class="icon-eye-open"></i> Preview Slide</a>
				<br /><br />
                
                
                <a href="javascript:void(0)" id="button_add_layer" class="btn btn-success btn-small"><i class="icon-align-left"></i> Add Layer: Text</a>
				<a href="javascript:void(0)" id="button_add_layer_image" class="btn btn-success btn-small margin_left10"><i class="icon-picture"></i> Add Layer: Image</a>
				<a href="javascript:void(0)" id="button_add_layer_video" class="btn btn-success btn-small margin_left10"><i class="icon-facetime-video"></i> Add Layer: Video</a>
				
	
				<a href="javascript:void(0)" id="button_delete_layer" class="btn btn-small btn-danger margin_left10 button-disabled">Delete Layer</a>
				<a href="javascript:void(0)" id="button_delete_all" class="btn btn-small btn-danger margin_left10 button-disabled">Delete All Layers</a>
				
				<a href="javascript:void(0)" id="button_duplicate_layer" class="btn btn-small btn-primary margin_left10 button-disabled">Duplicate Layer</a>
		
				
			</div>
								
			<div id="divLayers" class="slide_layers" style="<?php echo $this->styleLayers?>"></div>
			
			<div class="clear"></div>
			<div class="vert_sap"></div>

			<div id="global_timeline" class="timeline">
				<div id="timeline_handle" class="timerdot"></div>
				<div id="layer_timeline" class="layertime"></div>
				<div class="mintime">0 ms</div>
				<div class="maxtime"><?php echo $this->slideDelay?> ms</div>
			</div>
			
			<div class="layer_props_wrapper">
				
				<div class="edit_layers_left">
				
					<form name="form_layers" id="form_layers">
					<div class="unite-postbox postbox-left">
						<div class="postbox-head">
							<span class='head-text'>Layer Properties</span>
						</div>
						<div class="postbox-inner unitefields">
							<ul>
								<li>
									<?php
										UniteFunctionJoomlaRev::putFormField($this->formLayer,"layer_caption"); 
									?>
									<div id="layer_captions_down" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-s"></span></div>									
									<a id="button_edit_css" class="button-secondary" href="javascript:void(0)">Edit CSS File</a>									
								</li>
								<li>
									<a id="linkInsertButton" class="disabled" href="javascript:void(0)">insert button</a>
									<?php
										UniteFunctionJoomlaRev::putFormField($this->formLayer,"layer_text"); 
									?>									
								</li>
									<?php
										$this->putLayersField("layer_image_link"); 
										$this->putLayersField("layer_link_open_in");
									?> 
								<li>
									<div class="layer_animation_row">
										<?php $this->putLayersFieldRow("layer_animation"); ?>	
									</div>
									<div class="layer_easing_row">
										<?php $this->putLayersFieldRow("layer_easing");?>
									</div>
								</li>
									<?php  										
										$this->putLayersField("layer_speed");
										$this->putLayersField("layer_hidden"); 
									?>
								<li>
									<div class="layer_left_row">
										<?php $this->putLayersFieldRow("layer_left"); ?>	
									</div>
									<div class="layer_top_row">
										<?php $this->putLayersFieldRow("layer_top");?>
									</div>
									<span id="button_edit_video_row" style="display:none;">
										<input id="button_edit_video" type="button" value="Edit Video">
									</span>
								</li>
								
								<?php
 									 $this->putSlideLinkField(true);
									 $this->putLayersField("layer_video_autoplay");
 								?>								
								<li class="attribute_title">
									<span class="setting_text_2 text-disabled" original-title="">End Transition (optional)</span>
									&nbsp;&nbsp;&nbsp;
									<a id="link_show_end_params" class="link_show_end_params" href="javascript:void(0)">Show End Params</a>
									<hr>										
								</li>
								<?php									 
									 $this->putLayersField("layer_endtime","hidden");
									 $this->putLayersField("layer_endspeed","hidden");
									 $this->putLayersField("layer_endanimation","hidden");
									 $this->putLayersField("layer_endeasing","hidden");
								?>
							</ul>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</div>
					</form>
				</div>
				
				<div class="edit_layers_right">
					<div class="unite-postbox layer_sortbox">
						<div class="postbox-head">
							<span class='head-text'>Layer Sorting</span>
							<div id="button_sort_visibility" title="Hide All Layers"></div>
							<div id="button_sort_time" class="ui-state-active ui-corner-all button_sorttype"><span>By Time</span></div>
							<div id="button_sort_depth" class="ui-state-hover ui-corner-all button_sorttype"><span>By Depth</span></div>
						</div>
						<div class="postbox-inner">
							<ul id="sortlist" class='sortlist'></ul>
						</div>
					</div>
				</div>
			</div>
	
	<div id="dialog_edit_css" class="dialog_edit_file" title="Edit captions.css file" style="display:none;">
		<p>
			<textarea id="textarea_edit" rows="25" cols="100"></textarea>
		</p>
		<div class='unite_error_message' id="dialog_error_message" style="display:none;"></div>
		<div class='unite_success_message' id="dialog_success_message" style="display:none;"></div>
	</div> 
	
	<div id="dialog_insert_button" class="dialog_insert_button" title="Insert Button" style="display:none;">
		<p>
			<ul class="list-buttons">
			<?php foreach($this->arrButtonClasses as $class=>$text): ?>
					<li>
						<a href="javascript:UniteLayersRev.insertButton('<?php echo $class?>','<?php echo $text?>')" class="tp-button <?php echo $class?> small"><?php echo $text?></a>
					</li>
			<?php endforeach;?> 
			</ul>
		</p>
	</div>


	<script type="text/javascript">
		
		jQuery(document).ready(function() {
			
			<?php if(!empty($this->jsonLayers)):?>
				//set init layers object
				UniteLayersRev.setInitLayersJson(<?php echo $this->jsonLayers?>);
			<?php endif?>

			<?php if(!empty($this->jsonCaptions)):?>
			UniteLayersRev.setInitCaptionClasses(<?php echo $this->jsonCaptions?>);
			<?php endif?>
			
			UniteLayersRev.setCssCaptionsUrl('<?php echo GlobalsUniteRev::$urlCaptionsCss?>'); 
			UniteLayersRev.init("<?php echo $this->slideDelay?>");
		});
	
	</script>
			
			