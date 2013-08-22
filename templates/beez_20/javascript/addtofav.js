    jQuery(document).ready(function(){
        jQuery(".addtofav_button").click(function(){
            var curform = jQuery(this).parent();
            jQuery("form#filters").find("input:checked").each(function(i,n){
                var name = jQuery(n).attr("name");
                var value = jQuery(n).attr("value");
                jQuery(curform).append("<input type='hidden' value='"+value+"' name='"+name+"' />")
            });
        });
    });