<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$itemid = JRequest::getInt('Itemid',  1);
$tt_item=0;
$i = 0;

$fav_stats = mod_virtuemart_favorite_stats::getstats($num_prodstat);
if (count($fav_stats) == 0) { echo JText::_('VM_STATS_NOST');}
else {
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?php
  foreach ($fav_stats as $fav_stat) {
      if ($i == 0) {
          $sectioncolor = "sectiontableentry2";
          $i += 1;
      }
      else {
          $sectioncolor = "sectiontableentry1";
          $i -= 1;
      } 
      if( !$fav_stat->category_layout ) {
      	$category_layout = "default";
      }
      else {
      	$category_layout = $fav_stat->category_layout;
      }
      $tt_item++;
	  $pid = $fav_stat->product_parent_id ? $fav_stat->product_parent_id : $fav_stat->product_id;
	  $link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$pid.'&virtuemart_category_id='.
$fav_stat->virtuemart_category_id);
      ?>
    <tr class="<?php echo $sectioncolor ?>">
      <td width="15%"><?php printf("%02d", $tt_item); ?></td>
      <td width="85%">
        <a href="<?php echo $link; ?>"><?php echo $fav_stat->product_name; ?></a>
        <?php if ($prodlike_enabled == 1) { ?>
			<br />
            <?php echo JText::_('VM_TOTAL_SHARE'). ' '; ?> 
            <b> <?php echo $fav_stat->Cnt; ?></b> <?php echo ' '.JText::_('VM_TOTAL_TIME'); 
		} ?>
      </td>
    </tr>
    <?php } if ($prodstat_enabled == 1 || $favstat_enabled == 1 || $sharestat_enabled == 1 || $wishstat_enabled == 1) {?>
  <tr class="<?php echo $sectioncolor ?>">
      <td colspan="2">
      <?php echo JText::_('VM_STATS_TITLE'); ?>
      </td>
  </tr>
  <?php } if ($prodstat_enabled == 1) {?>
  <tr class="<?php echo $sectioncolor ?>">
      <td colspan="2">
      <?php echo JText::_('VM_TOTAL_PRODUCTS').' '; ?><b><?php echo $fav_stat->prod_cnt; ?></b>
      </td>
  </tr>
  <?php } if ($favstat_enabled == 1) {?>
  <tr class="<?php echo $sectioncolor ?>">
      <td colspan="2">
      <?php echo JText::_('VM_TOTAL_FAVLISTS').' '; ?><b><?php echo $fav_stat->fav_cnt; ?></b>
      </td>
  </tr>
   <?php } if ($sharestat_enabled == 1) {?>
  <tr class="<?php echo $sectioncolor ?>">
      <td colspan="2">
      <?php echo JText::_('VM_TOTAL_SHLISTS').' '; ?><b><?php echo $fav_stat->sh_cnt; ?></b>
      </td>
  </tr>
   <?php } if ($wishstat_enabled == 1) {?>
  <tr class="<?php echo $sectioncolor ?>">
      <td colspan="2">
      <?php echo JText::_('VM_WISHLISTS').' '; ?><b><?php echo $fav_stat->wish_cnt; ?></b>
      </td>
  </tr>
  <?php }} ?>
</table>
