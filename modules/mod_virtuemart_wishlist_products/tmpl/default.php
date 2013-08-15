<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$itemid = JRequest::getInt('Itemid',  1);
$tt_item=0;
$i = 0;

if ($user->id == "") { echo JText::_('VM_FAVORITE_LOGIN');}
else {
	$fav_products = mod_virtuemart_favorite_products::getfavorites($user->id,$num_favorites);
	if (count($fav_products) == 0) { echo JText::_('VM_FAVORITE_NOFAV');}
	else {
?>
<!--
<table style="border:0" cellpadding="0" cellspacing="0" width="100%">
<?php

  foreach ($fav_products as $fav_product) {
      if ($i == 0) {
          $sectioncolor = "sectiontableentry2";
          $i += 1;
      }
      else {
          $sectioncolor = "sectiontableentry1";
          $i -= 1;
      } 
      if( !$fav_product->category_layout ) {
      	$category_layout = "default";
      }
      else {
      	$category_layout = $fav_product->category_layout;
      }
      $tt_item++;
	  $pid = $fav_product->product_parent_id ? $fav_product->product_parent_id : $fav_product->product_id;
	  $link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$pid.'&virtuemart_category_id='.
$fav_product->virtuemart_category_id);
      ?>
    <tr class="<?php echo $sectioncolor ?>">
      <td width="15%"><?php printf("%02d", $tt_item); ?></td>
      <td width="85%">
        <a href="<?php echo $link; ?>"><?php echo $fav_product->product_name; ?></a>
      </td>
    </tr>
    <?php 
  } ?>
</table><br />
-->
 <a class="current_town" href="<?php echo JRoute::_("index.php?option=com_wishlist&view=favoriteslist&Itemid={$itemid}"); ?>"> <?php echo JText::_('VM_ALL_FAVORITE_PRODUCTS') ?></a><?php if($share_enabled) { ?>
	 <br />
	 <a class="current_town" href="<?php echo JRoute::_("index.php?option=com_wishlist&view=favoritessh&Itemid={$itemid}"); ?>"> <?php echo JText::_('VM_SHARE_FAVORITES') ?></a>
<?php 
}}}
?>
