<?php // no direct access
defined('_JEXEC') or die('Restricted access');
if($text == "search..."){
    $text = "поиск товаров";
}
?>
<!--BEGIN Search Box -->
<form id="search-vm" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&search=true&limitstart=0&virtuemart_category_id='.$category_id ); ?>" method="get">


<div id="hsearch" style="padding-left:10px;padding-top:3px;">
<div style="cursor:pointer; float: right; background: none; height: 25px; width: 25px; margin-right: 9px; margin-top: -2px;" onclick="javascript: document.forms['search-vm'].submit();"></div>
    <?php echo '<input style="height:20px; width:125px;" name="keyword" id="mod_virtuemart_search" type="text" value="'.$text.'"  onblur="if(this.value==\'\') this.value=\''.$text.'\';" onfocus="if(this.value==\''.$text.'\') this.value=\'\';" />'; ?>
    <input type="hidden" name="limitstart" value="0" />
    <input type="hidden" name="option" value="com_virtuemart" />
    <input type="hidden" name="view" value="category" />
</div>
</form>
<!-- End Search Box -->