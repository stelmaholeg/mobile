<?php
	$urlCurrentUrl = UniteFunctionJoomlaRev::getViewUrl(GlobalsUniteRev::VIEW_SLIDER,"edit","id=".$this->sliderID);
	$urlExport = $urlCurrentUrl."&client_action=export_slider";
	
?>

	<div id="toolbox_wrapper" class="toolbox_wrapper" style="display:none;">
	
		<div class="api-caption">Export / Import slider:</div>
		<div class="api-desc">Note, that when you importing slider, it delete all the current slider settings and slides, then replace it with the new ones.</div>
		
		<br>
		
		<a id="button_export_slider" class='button-secondary' href='<?php echo $urlExport?>' id="button_export_slider" >Export Slider</a>
		
		<br><br><br>
		
		<form action="<?php echo $urlCurrentUrl ?>" enctype="multipart/form-data" method="post">
		    
		    <input type="hidden" name="client_action" value="import_slider">
		    
		    Choose the import file:   
		    <br>
			<input type="file" name="import_file" class="input_import_slider">
			
			<input type="submit" class='button-secondary' value="Import Slider">
		</form>		
				
	</div>
	
	