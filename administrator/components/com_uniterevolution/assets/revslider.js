var UniteRevSlider = new function(){
	
	var t = this;
	var containerID = "slider_container";
	var container,arrow_left,arrow_right,bullets_container;
	var caption_back,caption_text;
	var bulletsRelativeY = "";
	
	
	/**
	 * show slider view error, hide all the elements
	 */
	t.showSliderViewError = function(errorMessage){
		jQuery("#config-document").hide();
		UniteAdminRev.showErrorMessage(errorMessage);
	}
	
	
	/**
	 * init view - multiple sliders
	 */
	t.initSlidersView = function(){
		
		jQuery(".button_slider_preview").click(function(){
			var buttonID = this.id;
			var sliderID = buttonID.replace("button_preview_","");
			var sliderTitle = jQuery(this).data("title");
			
			openPreviewSliderDialog(sliderID,"Preview "+sliderTitle);
		});		
		
	}
	
	
	/**
	 * init visual form width
	 */
	t.initSliderView = function(){
		
		//init save slider buttons
		jQuery("#button_save_duplicate").click(function(){
			initSaveSliderButton("update_slider_duplicate",this.id);
		});
		
		jQuery("#button_save,#button_save_close").click(function(){
			initSaveSliderButton("update_slider",this.id);
		});
		
		jQuery("#button_cancel").click(function(){
			location.href=g_urlViewSliders;
		});
		
		
		//api inputs functionality:
		jQuery("#api_wrapper .api-input, .api_area").click(function(){
			jQuery(this).select().focus();
		});
		
		//api button functions:
		jQuery("#link_show_api").click(function(){
			jQuery("#api_wrapper").show();
			jQuery("#link_show_api").addClass("button-selected");
			
			jQuery("#toolbox_wrapper").hide();
			jQuery("#link_show_toolbox").removeClass("button-selected");
		});
		
		jQuery("#link_show_toolbox").click(function(){
			jQuery("#toolbox_wrapper").show();
			jQuery("#link_show_toolbox").addClass("button-selected");
			
			jQuery("#api_wrapper").hide();
			jQuery("#link_show_api").removeClass("button-selected");
		});
		
		//preview slider actions
		jQuery("#button_preview_slider").click(function(){
			openPreviewSliderDialog(g_sliderID);
		});
	}

	
	/**
	 * init items view funciton
	 */
	t.initItemsView = function(){
		
		//close button actions
		jQuery("#button_close").click(function(){
			location.href = g_urlSliders;
		});
		
		//preview slider actions
		jQuery("#button_preview_slider_fromitems").click(function(){
			openPreviewSliderDialog(g_sliderID);
		});

		//set the items list sortable
		jQuery( "#list_items" ).sortable({
				axis:'y',
				handle:".col-handle-inside",
				update: function(){
					updateSlidesOrder();
				}
		});
		
		//publish / unpiblish actions
		jQuery( "#list_items li .publish_link").click(function(){
			var objLink = jQuery(this);
			var data = {};
			data.itemID = objLink.data("itemid");
			data.isPublished = objLink.data("published");
			
			var action = "unpublish_item";
			if(data.isPublished == "false" || data.isPublished == false)
				action = "publish_item";
			
			//send ajax request
			objLink.find(".state").hide();
			objLink.find(".publish_loader").show();			
			UniteAdminRev.ajaxRequest("toggle_publish_state",data,function(response){
				objLink.find(".state").show();
				objLink.find(".publish_loader").hide();
				
				//change state
				if(response.newstate == false){	//unpublish
					objLink.prop("title","Publish Item");
					objLink.find(".state").removeClass("publish").addClass("unpublish");
					objLink.find(".text").html("Unpublished");
					objLink.data("published","false");
				}else{
					objLink.prop("title","Unpublish Item");
					objLink.find(".state").removeClass("unpublish").addClass("publish");
					objLink.find(".text").html("Published");
					objLink.data("published","true");					
				}
				
			});			
		}); //end publish
		
		
		/**
		 * delete item
		 */
		jQuery("#list_items li .button_delete_slide").click(function(){
			var objButton = jQuery(this);
			var itemID = objButton.data("itemid");
			if(confirm("Are you sure to delete this slide?") == false)
				return(false);
			
			objButton.hide();
			objButton.siblings(".deleting_slide_loader").show();
			
			var data = {itemID:itemID};
			
			UniteAdminRev.ajaxRequest("delete_slide",data,function(response){
				jQuery("#list_items li#item_"+itemID).remove();
			});
			
		});
		
		
		/**
		 * duplicate items
		 */
		jQuery("#list_items li .button_duplicate_slide").click(function(){
			var objButton = jQuery(this);
			var objLoader = objButton.siblings(".duplicate_slide_loader");
			var itemID = objButton.data("itemid");
			
			var data = {itemID:itemID};
			
			objButton.hide();			
			objLoader.show();
			var data = {itemID:itemID};
			UniteAdminRev.ajaxRequest("duplicate_slide",data,function(response){
				reloadSlidesView();
			});
			
		});
		
		
		
		/**
		 * add slide button
		 */
		jQuery("#button_new_slide").click(function(){
			
			buttonID = "button_new_slide";
			
			//collect data
			var data = {
					sliderid:g_sliderID,
				};
			
			UniteAdminRev.setAjaxHideButtonID(buttonID);
			UniteAdminRev.setAjaxLoaderID(buttonID+"_loader");
			UniteAdminRev.setSuccessMessageID(buttonID+"_success_message");
			UniteAdminRev.ajaxRequest("add_slide",data,function(response){
				reloadSlidesView();
			});
			
		});
		
	}
	
	/**
	 * reload slides view, hide some html elements
	 */
	var reloadSlidesView = function(){
		
		jQuery("#loader_reloading").show();
		jQuery("#items_list_wrapper").hide();
		jQuery("#button_preview_slider_fromitems").hide();
		jQuery("#link_slider_settings").hide();
		
		window.location.reload();		
	}
	
	
	/**
	 * send ajax request to update order
	 */
	var updateSlidesOrder = function(){
		
		var arrHtmlIDs = jQuery( "#list_items" ).sortable("toArray");
		var arrIds = [];
		
		for(i=0;i<arrHtmlIDs.length;i++){
			arrIds.push(arrHtmlIDs[i].replace("item_",""));
		}
		
		//collect data
		var data = {
				sliderid:g_sliderID,
				arrIDs:arrIds
		};

		UniteAdminRev.setAjaxLoaderID("order_status_text");
		UniteAdminRev.ajaxRequest("update_items_order",data,function(response){});	
		
		updateSlidesNumbers();
	}
	
	/**
	 * update slide numbers after reorder or add slide
	 */
	var updateSlidesNumbers = function(){
		
		var counter = 0;
		jQuery("#list_items li .label_numitem").each(function(){
			counter++;
			jQuery(this).html(counter);
		});
		
	}
	
	/**
	 * open preview slider dialog
	 */
	var openPreviewSliderDialog = function(sliderID,title){
		
		if(!title)
			var title = jQuery("#dialog_preview_sliders").attr("title");
		
		jQuery("#dialog_preview_sliders").dialog({
			modal:true,
			resizable:false,
			minWidth:1100,
			minHeight:500,
			closeOnEscape:true,
			title:title,
			buttons:{
				"Close":function(){
					jQuery(this).dialog("close");
				}
			},
			open:function(event,ui){
				var form1 = jQuery("#form_preview")[0];
				jQuery("#preview_sliderid").val(sliderID);
				form1.action = g_urlAjax;
				form1.submit();
			}
		});
	}
	
	
	/**
	 * init the save slider ajax button
	 */
	var initSaveSliderButton = function(action,buttonID){
		
		UniteAdminRev.setAjaxHideButtonID(buttonID);
		UniteAdminRev.setAjaxLoaderID(buttonID+"_loader");
		UniteAdminRev.setSuccessMessageID(buttonID+"_success_message");
		
		//collect data
		var data = {
				sliderid:g_sliderID,
				settings:UniteSettingsRev.getSettingsObject("form_slider_settings"),
				params:UniteSettingsRev.getSettingsObject("form_slider_params")
			};
		
		UniteAdminRev.ajaxRequest(action ,data,function(response){
			switch(action){
				case "update_slider":
					//save and close
					if(buttonID == "button_save_close")
						 location.href=g_urlViewSliders;
					else
					 if(!g_sliderID){
						redirectToSlider(response.sliderID);
					 }
				break;
				case "update_slider_duplicate":
					redirectToSlider(response.newSliderID);
				break;
			}
		});
		
	}
	
	
	/**
	 * redirect to some slider
	 */
	var redirectToSlider = function(newSliderID){
		var urlNewSlider = g_viewSliderPattern+newSliderID;
		UniteAdminRev.showSystemMessage("Redirecting... Please wait a second!");
		location.href = urlNewSlider;
	}
	
	
	/* ===================== Item View Section =================== */
	
	/**
	 * init item view
	 */
	t.initItemView = function(slideID,sliderID){
		
		initImageChangeButton();
		
		checkItemViewHash();
		
		jQuery("#button_save_slide").click(function(){
			saveSlideOperation("update_slide",this.id);
		});
		
		//save && new
		jQuery("#button_save_slide_new").click(function(){
			saveSlideOperation("update_slide_new",this.id);
		});

		//save && close
		jQuery("#button_save_slide_close").click(function(){
			saveSlideOperation("update_slide_close",this.id);
		});		

		//save && copy
		jQuery("#button_save_slide_copy").click(function(){
			saveSlideOperation("update_slide_duplicate",this.id);
		});
		
		//cancel
		jQuery("#button_cancel").click(function(){
			location.href=g_urlViewItems;
		});
		
		// slide options hide / show			
		jQuery("#link_hide_options").click(function(){
			
			if(jQuery("#fieldset_slide_inside").is(":visible") == true){
				jQuery("#fieldset_slide_inside").hide("slow");
				jQuery(this).text("Show Slide Options").addClass("link-selected");
			}else{
				jQuery("#fieldset_slide_inside").show("slow");
				jQuery(this).text("Hide Slide Options").removeClass("link-selected");
			}
			
		});


		//preview slide actions - open preveiw dialog			
		jQuery("#button_preview_slide").click(function(){
			
			var iframePreview = jQuery("#frame_preview");
			var previewWidth = iframePreview.width() + 10;
			var previewHeight = iframePreview.height() + 10;
			var iframe = jQuery("#frame_preview");
			
			jQuery("#dialog_preview").dialog({
					modal:true,
					resizable:false,
					minWidth:previewWidth,
					minHeight:previewHeight,
					closeOnEscape:true,
					buttons:{
						"Close":function(){
							jQuery(this).dialog("close");
						}
					},
					open:function(event,ui){
						var form1 = jQuery("#form_preview")[0];
						
						var objData = {
								slideid:slideID,
								sliderid:sliderID,
								params:UniteSettingsRev.getSettingsObject("form_slide_params"),
								layers:UniteLayersRev.getLayers(),
								image:jQuery("#jform_params_image").val()
							};
						
						var jsonData = JSON.stringify(objData);
						
						jQuery("#preview_slide_data").val(jsonData);
						form1.action = g_urlAjax;
						form1.client_action = "preview_slide";
						form1.submit();													
					}
			});
		});
		
	}
	
	/**
	 * check hash of item view, if it has slide id on it, redirect to the item page
	 */
	var checkItemViewHash = function(){

		//check hash and redirect:
		var hash = location.hash;
		if(!hash || hash == "") 
			return(false);
		
		var slideID = hash.replace("#slideid=","");
		redirectToSlide(slideID);
	}
	
	/**
	 * redirect to some slide
	 */
	var redirectToSlide = function(slideID){
		var urlViewItem = g_viewItemPattern+slideID;
		jQuery("#edit_slide_wrapper").hide();
		UniteAdminRev.showSystemMessage("Redirecting... Please wait a second!");
		location.href=urlViewItem;
	}
	
	
	/**
	 * do save slide operation
	 */
	var saveSlideOperation = function(action,buttonID){
		
		//collect data
		var data = {
				sliderid:g_sliderID,
				slideid:g_slideID,
				params:UniteSettingsRev.getSettingsObject("form_slide_params"),
				layers:UniteLayersRev.getLayers()
			};
		
		data.params.image = jQuery("#jform_params_image").val();
		
		UniteAdminRev.setAjaxHideButtonID(buttonID);
		UniteAdminRev.setAjaxLoaderID(buttonID+"_loader");
		UniteAdminRev.setSuccessMessageID(buttonID+"_success_message");
		UniteAdminRev.ajaxRequest(action ,data,function(response){
			
			//do after save operations according action type
			
			switch(action){
				case "update_slide_new":					
					UniteAdminRev.showSystemMessage("Opening new slide...");
					location.href = g_viewItemNew;					
				break;
				case "update_slide_close":
					UniteAdminRev.showSystemMessage("Closing...");
					location.href=g_urlViewItems;
				break;
				case "update_slide":
					updateSlideIDAndHash(response.slideID);
				break;
				case "update_slide_duplicate":
					redirectToSlide(response.slideID);
				break;
				default:
					UniteAdminRev.showErrorMessage("Wrong save action: "+action);
				break;
			}
			
		});
	}
	
	
	/**
	 * update global slideid and hash
	 */
	var updateSlideIDAndHash = function(newSlideID){
		if(!g_slideID || g_slideID == ""){
			g_slideID = newSlideID;
			location.hash = "slideid="+g_slideID;
		}		
	}
	
	
	/**
	 * init image change button
	 */
	var initImageChangeButton = function(){
		
		//operate on slide image change
		var obj = document.getElementById("jform_params_image");
		obj.addEvent('change',function(){
			var imageUrl = g_urlBase + this.value;
			jQuery("#divLayers").css("background-image","url("+imageUrl+")");
		});
		
	}
	
	
	/* ===================== Item View End =================== */
	
}
	