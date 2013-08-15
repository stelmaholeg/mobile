
var UniteSettingsRev = new function(){
	
	var t=this;
	var colorMoveEventFunc = null;
		
	/**
	 * get object of settings values
	 */
	t.getSettingsObject = function(formID){		
		var obj = new Object();
		var form = document.getElementById(formID);
		var name,value,type,flagUpdate;
		
		//enabling all form items connected to mx
		for(var i=0; i<form.elements.length; i++){
			var formElement = form.elements[i];
			var id = formElement.id;
			var name = formElement.name;		
			var value = formElement.value;
			var type = formElement.type;
			
			var elementName = id;	//set the element name to be inserted in the object
			
			flagUpdate = true;
			switch(type){
				case "checkbox":
					value = form.elements[i].checked;
				break;
				case "radio":
					if(formElement.checked == false) 
						flagUpdate = false;
					else{
						//set element name
						var objParent = jQuery(formElement).parent();
						if(objParent.get(0).tagName == "FIELDSET" && objParent.hasClass("radio"))
							elementName = objParent.attr("id");
					}
				break;				
			}
			
			//id = trim(id);
			if(flagUpdate == true && id != undefined && id != "") obj[elementName] = value;
		}
		return(obj);
	}
	
	
	/**
	 * on select or radio setting change, check and operate controls
	 */
	var onSettingChange = function(){
		
		var objInput = jQuery(this);
		
		//set input type
		var inputType = "";
		if(this.tagName == "SELECT")
			inputType = "select";
		else
			inputType = objInput.attr("type");
		
		switch(inputType){
			case "radio":
				var fieldset = objInput.parent();
				var inputID = fieldset.attr("id");
			break;
			case "select":
				var inputID = objInput.attr("id");
			break;
			default:
				return(false);
			break;
		}
				
			if(!g_controls[inputID]) return(false);
			
			var controlValue = objInput.val();
			
			jQuery(g_controls[inputID]).each(function(){
								
				var childID = this.child;
				var childInput = jQuery("#"+childID);
					
				switch(this.ctype){
					case "enable":
					case "disable":	
						
						if(childInput.length){
							if(this.ctype == "enable" && controlValue != this.value || this.ctype == "disable" && controlValue == this.value){
								
								childInput.prop("disabled","disabled");		//disable
								jQuery("#"+childID+"-lbl,#"+childID+"-unit").addClass("field_disabled");
								
								//childInput.css("color","");	//in case of color picker
							}
							else{
								
								childInput.prop("disabled","");
								jQuery("#"+childID+"-lbl,#"+childID+"-unit").removeClass("field_disabled");
								
								//if(jQuery(childInput).hasClass("inputColorPicker")) g_picker.linkTo(childInput);
			 				}
						}
						
					break;
					case "show":
					case "hide":						
						if(this.ctype == "show" && controlValue == this.value || this.ctype == "hide" && controlValue != this.value){
							//show
							jQuery("#"+childID).show().removeClass("hidden");
							jQuery("#"+childID+"-lbl").show().removeClass("hidden");
							jQuery("#"+childID+"-unit").show().removeClass("hidden");
							
						}else{	
							//hide
							jQuery("#"+childID).hide().addClass("hidden");;
							jQuery("#"+childID+"-lbl").hide().addClass("hidden");
							jQuery("#"+childID+"-unit").hide().addClass("hidden");;
						}
					break;
			   }
				
			});
			
					
	}	

	/**
	 * init color pickers input
	 */
	var initColorPickers = function(){
		//appent div to the body
		var fields = jQuery("input.color-picker");
		if(fields.length == 0)	
			return(false);
		
		jQuery("body").append("<div id='farbtastic_wrapper' class='farbtastic_wrapper' style='display:none;'><div id='farb_picker'></div></div>");
		
		var picker = jQuery.farbtastic('#farb_picker');
		var wrapper = jQuery("#farbtastic_wrapper");
		 
		fields.each(function(){
			picker.linkTo(this);
		});
		
		fields.focus(function(){
			wrapper.show();
			picker.linkTo(this);
			var input = jQuery(this);
			var offset = input.offset();
			
			//set picker position
			wrapper.css({
				"left":offset.left + input.width()+20,
				"top":offset.top - wrapper.height() + 150
			});
			
		}).click(function(){			
			return(false);	//prevent body click
		});
		
		wrapper.click(function(){
			return(false);	//prevent body click
		});
		
		jQuery("body").click(function(){
			wrapper.hide();
		});
	}
	
	
	/**
	 * init checkbox form field
	 */
	var initCheckboxes = function(){
		jQuery(".mycheckbox_check").click(function(){
			var strChecked = this.checked?"true":"false";
			jQuery(this).siblings(".mycheckbox_input").val(strChecked);
		});
	}
	
	/**
	 * init the controls
	 */
	var initControls = function(){
		
		//init radio controls
		jQuery(".unitefields fieldset.radio input[type='radio']").change(onSettingChange);
		jQuery(".unitefields select").change(onSettingChange);
		
	}
	
	/**
	 * set color picker move event function.
	 */
	t.onColorPickerMove = function(func){
		colorMoveEventFunc = func;
	}
	
	/**
	 * on color picker move event. pass event to stored functions.
	 */
	t.onColorPickerMoveEvent = function(){
		if(colorMoveEventFunc) colorMoveEventFunc();
	}
	
	/**
	 * init the settings function, set the tootips on sidebars.
	 */
	var init = function(){
		initColorPickers();
		initCheckboxes();
		initControls();
	}
	
	//call "constructor"
	jQuery(document).ready(function(){
		init();
	});
	
} // UniteSettings class end


