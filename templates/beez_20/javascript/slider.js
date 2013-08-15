	var currentImage;
    var currentIndex = -1;
    var interval;
    function showImage(index){
        if(index < jQuery('#bigPic img').length){
        	var indexImage = jQuery('#bigPic img')[index]
            if(currentImage){   
            	if(currentImage != indexImage ){
                    jQuery(currentImage).css('z-index',2);
                    clearTimeout(myTimer);
                    jQuery(currentImage).fadeOut(250, function() {
					    myTimer = setTimeout("showNext()", 3000);
					    jQuery(this).css({'display':'none','z-index':1})
					});
                }
            }
			
            jQuery(indexImage).css({'display':'block', 'opacity':1});
            currentImage = indexImage;
            currentIndex = index;
            jQuery('#thumbs li').removeClass('active');
            jQuery(jQuery('#thumbs li')[index]).addClass('active');
        }
    }
    
    function showNext(){
        var len = jQuery('#bigPic img').length;
        var next = currentIndex < (len-1) ? currentIndex + 1 : 0;
        showImage(next);
    }
    
    var myTimer;
    
    jQuery(document).ready(function() {
	    myTimer = setTimeout("showNext()", 3000);
		showNext(); //loads first image
        jQuery('#thumbs li').bind('click',function(e){
        	var count = jQuery(this).attr('rel');
        	showImage(parseInt(count)-1);
        });
	});
	
