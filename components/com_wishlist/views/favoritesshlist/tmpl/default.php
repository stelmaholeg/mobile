<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

//Addding Main CSS/JS VM_Theme files to header
JHTML::stylesheet("theme.css", VM_THEMEURL);
JHTML::stylesheet("template.css", "components/com_wishlist/");

$itemid = JRequest::getInt('Itemid',  1);
$i = 0;
$my_page =& JFactory::getDocument();
$conf =& JFactory::getConfig();
$sitename = $conf->getValue('config.sitename');
$my_page->setTitle($sitename. ' - ' .JText::_( 'VM_SHARED_LIST' )); 
?>

<table width="100%">
	<thead>
    <tr>
		<th colspan="5">
			<span class="fav_title"><?php echo JText::_( 'VM_SHARED_LIST' ); ?></span>
		</th>
	</tr>
	<tr class="table_header">
    <th class="jcb_fieldDiv jcb_fieldLabel">
		<?php echo JText::_( 'FW_TYPE' ); ?>
	</th>
	<th class="jcb_fieldDiv jcb_fieldLabel">
		<?php echo JText::_( 'SHARE_DATE' ); ?>
	</th>
	<th class="jcb_fieldDiv jcb_fieldLabel">
		<?php echo JText::_( 'USER_NAME' ); ?>
	</th>
	<th class="jcb_fieldDiv jcb_fieldLabel">
		<?php echo JText::_( 'SHARE_TITLE' ); ?>
	</th>
	</tr>
	</thead>
	<tbody>
<?php foreach($this->data as $dataItem): 
 if ($i == 0) {
      $sectioncolor = "sectiontableentry2";
      $i += 1;
}
else {
      $sectioncolor = "sectiontableentry1";
      $i -= 1;
}
?> 
<?php
	$link = JRoute::_( "index.php?option=com_wishlist&view=sharelist&user_id={$dataItem->user_id}&Itemid={$itemid}" );
?>
	<tr class="<?php echo $sectioncolor ?>">
    <td class="jcb_fieldDiv jcb_fieldValue">
		<?php if ($dataItem->isWishList) { ?>
        <img src="components/com_wishlist/images/wishlist.png" title="<?php echo JText::_( 'VM_WISHLIST_TRUE' ); ?>" alt="<?php echo JText::_( 'VM_WISHLIST_TRUE' ); ?>" />
        <?php } 
		else {
		?>
        <img src="components/com_wishlist/images/favorites.png" title="<?php echo JText::_( 'VM_FAVORITES_TRUE' ); ?>" alt="<?php echo JText::_( 'VM_FAVORITES_TRUE' ); ?>" />
        <?php } ?>
	</td>
    <td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo $dataItem->share_date; ?>
	</td>
	<td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo $dataItem->name; ?>
	</td>
	<td class="jcb_fieldDiv jcb_fieldValue">
		<?php echo $dataItem->share_title; ?>
	</td>
    <td>
		<!-- You can use $link var for link edit controller -->
		<a href="<?php echo $link; ?>">View</a>
	</td>
</tr>
<?php endforeach; ?>
	<tbody>
    <tfoot>
		<tr>
			<td colspan="5">
				<div class="jcb_pagination"><?php echo $this->pagination->getPagesLinks(); ?> - <?php echo $this->pagination->getPagesCounter(); ?></div>
			</td>
		</tr>
	</tfoot>
</table>

