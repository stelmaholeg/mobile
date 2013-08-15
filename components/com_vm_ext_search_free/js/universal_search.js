var url = 'http://'+location.hostname;
var spin = '<div style="width:100%;text-align:center;"><img src="'+url+'/components/com_vm_ext_search/img/spinner.gif" alt=""/></div>';
jQuery.noConflict();
  
//при изменении категории меняем все остальное
function categoryChange() {
    var qString = jQuery("#com_vm_search_form").formSerialize();
    jQuery("#com_typ_div").fadeTo("slow", 0.22);
    jQuery("#com_mf_div").fadeTo("slow", 0.22);
    jQuery.ajax({
        type: "POST",
        url: url+'/index2.php?task=ajax_com&no_html=1&task2=manufacturer',
        data: qString,
        dataType: 'HTML',
        success: function (data){
            jQuery("#com_mf_div").html(data);
            jQuery("#com_mf_div").fadeTo("slow", 1);
        }
    });
    jQuery.ajax({
        type: "POST",
        data: qString,
        url: url+'/index2.php?task=ajax_com&no_html=1&task2=typ',
        
        dataType: 'HTML',
        success: function (data){
            jQuery("#com_typ_div").html(data);
            jQuery("#com_typ_div").fadeTo("slow", 1);
            typeChange();
        }
    });
return;
}

function mfChangeMulti() {
    var qString = jQuery("#com_vm_search_form").formSerialize();
    jQuery("#com_typ_div").fadeTo("slow", 0.22);
//    jQuery("#com_harakt_div").slideUp("slow");
    jQuery.ajax({
        type: "POST",
        url: url+'/index2.php?task=ajax_com&no_html=1&task2=typ',
        data: qString,
        dataType: 'HTML',
        success: function (data){
            jQuery("#com_typ_div").html(data);
            jQuery("#com_typ_div").fadeTo("slow", 1);
            typeChange();
        }
    });
    return;
}

function typeChange(){
    var qString = jQuery("#com_vm_search_form").formSerialize();
    jQuery("#com_harakt_div").fadeTo("slow", 0.22);
    jQuery.ajax({
        type: "POST",
        url: url+'/index2.php?task=ajax_com&no_html=1&task2=harakt',
        data: qString,
        dataType: 'HTML',
        success: function (data){
            jQuery("#com_harakt_div").html(data);
            jQuery("#com_harakt_div").fadeTo("slow", 1);
        }
    });
    return;
}

function loadProduct( limitstart ){
    var qString = jQuery("#com_vm_search_form").formSerialize();
    jQuery("#product_print").fadeTo("slow", 0.22);
    jQuery.ajax({
        type: "POST",
        url: url+'/index2.php?task=ajax_com&task2=load_page&limitstart='+limitstart+'&no_html=1',
        data: qString,
        dataType: 'HTML',
        success: function (data){
            jQuery("#product_print").html(data);
            jQuery("#product_print").fadeTo("slow", 1);
        }
    });
    return;
}

function uncheck( name ){
    for (var obj = document.getElementsByName ( name ), j = 0; j < obj.length; j++) obj [j].checked = false;
    if (name == 'mf_id[]') mfChangeMulti();
    else typeChange();
}