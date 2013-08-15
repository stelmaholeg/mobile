var url = 'http://'+location.hostname;
var spin = '<div style="width:100%;text-align:center;"><img src="'+url+'/components/com_vm_ext_search/img/spinner.gif" alt=""/></div>';
jQuery.noConflict();
  
//при изменении категории меняем все остальное
function mod_categoryChange() {
    var qString = jQuery("#mod_vm_search_form").formSerialize();
    jQuery("#mod_typ_div").fadeTo("slow", 0.22);
    jQuery("#mod_mf_div").fadeTo("slow", 0.22);
    jQuery.ajax({
        type: "POST",
        url: url+'/index2.php?task=ajax_mod&no_html=1&task2=manufacturer',
        data: qString,
        dataType: 'HTML',
        success: function (data){
            jQuery("#mod_mf_div").html(data);
            jQuery("#mod_mf_div").fadeTo("slow", 1);
        }
    });
    jQuery.ajax({
        type: "POST",
        data: qString,
        url: url+'/index2.php?task=ajax_mod&no_html=1&task2=typ',
        dataType: 'HTML',
        success: function (data){
            jQuery("#mod_typ_div").html(data);
            jQuery("#mod_typ_div").fadeTo("slow", 1);
            mod_typeChange();
        }
    });
return;
}

function mod_mfChangeMulti() {
    var qString = jQuery("#mod_vm_search_form").formSerialize();
    jQuery("#mod_typ_div").fadeTo("slow", 0.22);
//    jQuery("#com_harakt_div").slideUp("slow");
    jQuery.ajax({
        type: "POST",
        url: url+'/index2.php?task=ajax_mod&no_html=1&task2=typ',
        data: qString,
        dataType: 'HTML',
        success: function (data){
            jQuery("#mod_typ_div").html(data);
            jQuery("#mod_typ_div").fadeTo("slow", 1);
            mod_typeChange();
        }
    });
    return;
}

function mod_typeChange(){
    var qString = jQuery("#mod_vm_search_form").formSerialize();
    jQuery("#mod_harakt_div").fadeTo("slow", 0.22);
    jQuery.ajax({
        type: "POST",
        url: url+'/index2.php?task=ajax_mod&no_html=1&task2=harakt',
        data: qString,
        dataType: 'HTML',
        success: function (data){
            jQuery("#mod_harakt_div").html(data);
            jQuery("#mod_harakt_div").fadeTo("slow", 1);
        }
    });
    return;
}

function mod_loadProduct( limitstart ){
    var qString = jQuery("#mod_vm_search_form").formSerialize();
    jQuery("#main_search").fadeTo("slow", 0.22);
    jQuery.ajax({
        type: "POST",
        url: url+'/index2.php?task=ajax_mod&task2=load_page&limitstart='+limitstart+'&no_html=1',
        data: qString,
        dataType: 'HTML',
        success: function (data){
            jQuery("#main_search").html(data);
            jQuery("#main_search").fadeTo("slow", 1);
        }
    });
    return;
}

function mod_uncheck( name ){
    for (var obj = document.getElementsByName ( name ), j = 0; j < obj.length; j++) obj [j].checked = false;
    if (name == 'mf_id[]') mod_mfChangeMulti();
    else mod_typeChange();
}

