<?php
/**
 * @package	Joomla.Tutorials
 * @subpackage	Module
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license	License GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;

$vmcatid = JRequest::getVar('virtuemart_category_id');
$vmprodid = JRequest::getVar('virtuemart_product_id');
if(!$vmcatid || $vmprodid){
    return;
}
$array = modTestModuleHelper::getCurrentUsers();
if(!$array[0]){
    return;
}

?>

<div class="filter-list" style="border:solid 1px #cccccc;">
<!--<div class="mhead">
    <div class="lef-mhead"></div>
    <div class="mid-mhead">Категории товаров</div>
    <div class="rig-mhead"></div>
</div>-->
<?php if(JRequest::getString('mode') != "fav_add" && (JRequest::getString('view') == "productdetails" || JRequest::getString('view')=="cart")){
    echo "</div>";
    return;
} ?>
<?php
    $tmp_title = "";
    $tmp_array = array();
    $catid = $array[1];
    if(isset($_POST['f'])){
        $post = $_POST['f'];
    } else if(isset($_GET['f'])){
        $post = $_GET['f'];
    } else {
        $post = null;
    }
    echo "<form method=\"post\" action='/index.php?option=com_virtuemart&amp;view=category&amp;virtuemart_category_id=".$catid."' id='filters'>"; ?>
	<table cellpadding="0" cellspacing="0" style="width:188px;border:0">
    

    <?php if(isset($_REQUEST['f'])){
        echo "<tr><td colspan='2' style='text-align:center;padding-bottom:3px;'><a class='current_town' href='/index.php?option=com_virtuemart&view=category&virtuemart_category_id=".$catid."'>Сбросить фильтры</a></td></tr>";
    }
    $posti = 0;?>
        <tr class="filter-title expanded">
            <td class="filter-title" colspan="2">
            <span style="font-size:12px; border:none; margin-top:4px; margin-bottom:3px;" class="title">
                Цена
            </span>
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <div class="dem">
                    <p style="padding-top:5px;">
                        <input type="text" id="amount" style="border:0; color:#f6931f; font-weight:bold;" />
                    </p>
                    <div style="height:20px;" id="slider-range"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input id="famount" name="famount" type="hidden" value="<?php echo isset($_REQUEST['famount']) ? $_REQUEST['famount'] : 0;?>"/>
                <input id="tamount" name="tamount" type="hidden" value="<?php echo isset($_REQUEST['tamount']) ? $_REQUEST['tamount'] : 50000;?>"/>
                <a class="vmorder-link" style="margin-left: 127px; margin-top: 0px; display:block; margin-bottom:5px;" href="#" onclick='javascript: document.forms["filters"].submit(); return false;'>Показать</a>
            </td>
        </tr>
<?php
    $filter_counts = 0;
    $prev_id = -1;
    if(is_array($array[0])){
        foreach($array[0] as $id=>$el){
            if($tmp_title != $el->custom_title && $el->is_on_filterlist && $el->custom_title!="COM_VIRTUEMART_RELATED_PRODUCTS" && $el->custom_title!="COM_VIRTUEMART_RELATED_CATEGORIES"){
                $tmp_title = $el->custom_title;
                if($filter_counts>5){
                    echo "<tr class='filter-item showall' data-id='".$prev_id."'>".
                            "<td colspan='2' style='text-align:right;'><a href='javascript:' class='vmorder-link' style='margin-right:10px;'>показать все</a></td>".
                         "</tr>";
                }
                $prev_id = $el->virtuemart_custom_id;
                echo "<tr class='filter-title expanded' data-id='".$el->virtuemart_custom_id."'><td colspan='2' class='filter-title'>";
                    echo "<span class='title' style='font-size:12px; border:none; margin-top:4px; margin-bottom:3px;'>" . JText::_($el->custom_field_desc ? $el->custom_field_desc : $el->custom_title ) . "</span>";
                echo "</td></tr>";
                $tmp_array = array();
                $posti = 0;
                $filter_counts = 0;
            }
            $checked = "";

            if(isset($post[$el->virtuemart_custom_id]) && $post[$el->virtuemart_custom_id]==$el->custom_value){
                $checked = "checked='checked'";
            }
            if(is_array($post[$el->virtuemart_custom_id]) && in_array($el->custom_value,$post[$el->virtuemart_custom_id])){
                $checked = "checked='checked'";
            }

            if(!in_array($el->custom_value,$tmp_array) && $el->is_on_filterlist && $el->custom_title!="COM_VIRTUEMART_RELATED_PRODUCTS" && $el->custom_title!="COM_VIRTUEMART_RELATED_CATEGORIES"){
                $tmp_array[] = $el->custom_value;
                $filter_counts++;
                $filtervis = $filter_counts>5 ? "style='display:none;'" : "";
                echo "<tr ".$filtervis." class='filter-item' data-id='".$el->virtuemart_custom_id."'><td>";
                if($el->custom_value == ""){
                    //echo "<div class='checkbox'></div><input type='checkbox' name='f[".$el->virtuemart_custom_id."][".$posti."]' value='".$el->custom_value."' ".$checked." onclick='javascript: document.forms[\"filters\"].submit();' ></td><td>Не указано<br/>";
					echo "</td><td>";
                } else {
                    echo "<div class='checkbox'></div><input type='checkbox' name='f[".$el->virtuemart_custom_id."][".$posti."]' value='".$el->custom_value."' ".$checked." onclick='javascript: document.forms[\"filters\"].submit();'/></td><td>" . $el->custom_value . "";
                }
                $posti += 1;
                echo "</td></tr>";
            }
        }
    }
?>
<?php
    echo "</table>";
    echo "</form>";
?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(function() {
            jQuery( "#slider-range" ).slider({
                range: true,
                min: 0,
                max: 50000,
                step: 500,
                values: [
                    <?php echo (isset($_REQUEST['famount']) ? $_REQUEST['famount'] : 0); ?>,
                    <?php echo (isset($_REQUEST['tamount']) ? $_REQUEST['tamount'] : 50000); ?>],
                slide: function( event, ui ) {
                    jQuery( "#amount" ).val( "" + ui.values[ 0 ] + "руб. - " + ui.values[ 1 ] + "руб." );
                    jQuery( "#famount" ).val(ui.values[0]);
                    jQuery( "#tamount" ).val(ui.values[1]);
                }
            });
            jQuery( "#amount" ).val( "" + jQuery( "#slider-range" ).slider( "values", 0 ) + "руб. " +
                    " - " + jQuery( "#slider-range" ).slider( "values", 1 ) + "руб. ");
            jQuery( "#tamount" ).val(jQuery("#slider-range").slider("values",0));
            jQuery( "#tamount" ).val(jQuery("#slider-range").slider("values",1));
        });
        jQuery('tr.filter-item.showall').click(function(){
            var f = jQuery(this).attr('data-id');
            jQuery('tr.filter-item[data-id="'+f+'"]').show()
            jQuery(this).hide()
        })

        jQuery('tr.filter-item input[type="checkbox"]').each(function(i,n){
            var checked = jQuery(n).is(':checked');
            if(checked){
                jQuery(n).prev().addClass('checked');
            }
        });
        jQuery('tr.filter-title').click(function(){
            var f = jQuery(this).attr('data-id');
            if((this).hasClass('expanded')){
                $(this).removeClass('expanded');
                jQuery('tr.filter-item[data-id="'+f+'"]').hide()
            } else {
                $(this).addClass('expanded');
                jQuery('tr.filter-item[data-id="'+f+'"]').show()
            }
        });
        jQuery('.filter-list div.checkbox').click(function(){
            var checked = jQuery(this).next().is(':checked');
            if(checked){
                jQuery(this).removeClass('checked');
                jQuery(this).next().attr('checked',false);
                document.forms['filters'].submit();
            } else {
                jQuery(this).addClass('checked');
                jQuery(this).next().attr('checked',true);
                document.forms['filters'].submit();
            }
        });
    });
</script>