<?php
/**
 *
 * Show the products in a category
 *
 * @package	VirtueMart
 * @subpackage
 * @author RolandD
 * @author Max Milbers
 * @todo add pagination
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 5810 2012-04-05 23:10:14Z Milbo $
 */

//vmdebug('$this->category',$this->category);
vmdebug('$this->category '.$this->category->category_name);
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHTML::_( 'behavior.modal' );
/* javascript for list Slide
  Only here for the order list
  can be changed by the template maker
*/
$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { jQuery(this).find('.orderlist').stop().show()},
		function() { jQuery(this).find('.orderlist').stop().hide()}
	)
});
";

$document = JFactory::getDocument();
$document->addScriptDeclaration($js);

$db	=& JFactory::getDBO();
$query = "SELECT virtuemart_product_id
              FROM `#__virtuemart_order_items`
             GROUP BY virtuemart_product_id
             ORDER BY COUNT(virtuemart_product_id) DESC LIMIT 5";
$db->setQuery($query);
$populardata = $db->loadResultArray();
/*$edit_link = '';
if(!class_exists('Permissions')) require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'permissions.php');
if (Permissions::getInstance()->check("admin,storeadmin")) {
	$edit_link = '<a href="'.JURI::root().'index.php?option=com_virtuemart&tmpl=component&view=category&task=edit&virtuemart_category_id='.$this->category->virtuemart_category_id.'">
		'.JHTML::_('image', 'images/M_images/edit.png', JText::_('COM_VIRTUEMART_PRODUCT_FORM_EDIT_PRODUCT'), array('width' => 16, 'height' => 16, 'border' => 0)).'</a>';
}

echo $edit_link; */ ?>
<?php
/* Show child categories */

if ( VmConfig::get('showCategory',1) ) {
    if ($this->category->haschildren) {

        // Category and Columns Counter
        $iCol = 1;
        $iCategory = 1;

        // Calculating Categories Per Row
        $categories_per_row = VmConfig::get ( 'categories_per_row', 3 );
        $category_cellwidth = ' width'.floor ( 100 / $categories_per_row );

        // Separator
        $verticalseparator = " vertical-separator";
        ?>

        <!--
		<div class="category-view">

		<?php // Start the Output
        if(!empty($this->category->children) && JRequest::getString('keyword')==null){
            echo '<div style="clear:both; height:20px;"></div>';
            foreach ( $this->category->children as $category ) {

                // Show the horizontal seperator
                if ($iCol == 1 && $iCategory > $categories_per_row) { ?>
                    <div class="horizontal-separator"></div>
                    <?php }

                // this is an indicator wether a row needs to be opened or not
                if ($iCol == 1) { ?>
			<div class="row">
			<?php }

                // Show the vertical seperator
                if ($iCategory == $categories_per_row or $iCategory % $categories_per_row == 0) {
                    $show_vertical_separator = ' ';
                } else {
                    $show_vertical_separator = $verticalseparator;
                }

                // Category Link
                $caturl = JRoute::_ ( 'index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id );

                // Show Category ?>
                <div class="category floatleft<?php echo $category_cellwidth . $show_vertical_separator ?>">
                    <div class="spacer">
                        <h2>
                            <a class="current_town" href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>"><?php echo $category->category_name ?><?php echo $category->images[0]->displayMediaThumb("",false); ?></a>
                        </h2>
                    </div>
                </div>
                <?php
                $iCategory ++;

                // Do we need to close the current row now?
                if ($iCol == $categories_per_row) { ?>
                    <div class="clear"></div>
		</div>
			<?php
                    $iCol = 1;
                } else {
                    $iCol ++;
                }
            }
        }
        // Do we need a final closing row tag?
        if ($iCol != 1) { ?>
            <div class="clear"></div>
		</div>
	<?php } ?>
    </div>-->

    <?php }
}
?>
<div class="browse-view" <?php if(empty($this->products)){ echo 'style="margin-top:25px; text-align:center;"'; } ?>>
    <?php if ($this->search !==null ) { ?>
    <!--BEGIN Search Box -->
    <form id="search-cat" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&limitstart=0&virtuemart_category_id='.$this->category->virtuemart_category_id ); ?>" method="get">
        <div class="virtuemart_search">
            <div style="float:left; margin-top:-16px; margin-bottom:10px;">
                <?php echo $this->searchcustom ?>
            </div>
            <div style="float:right;" class="ie-search-box">
                <input name="keyword" class="inputbox" type="text" size="20" value="<?php echo $this->keyword ?>" />
                <input type="submit" value="<?php echo JText::_('COM_VIRTUEMART_SEARCH') ?>" class="addtocart-button" style="width: 162px; margin-left: 15px;" onclick="this.form.keyword.focus();"/>
            </div>
            <div class="clear"></div>
    </div>
        <input type="hidden" name="option" value="com_virtuemart" />
        <input type="hidden" name="search" value="true" />
        <input type="hidden" name="view" value="category" />

    </form>
    <!-- End Search Box -->
    <?php } ?>
    <?php
if (!empty($this->keyword)) {
    ?>
    <h3>Результаты поиска по запросу "<?php echo $this->keyword; ?>":</h3>
    <?php
} ?>

<?php // Show child categories
if (!empty($this->products)) {
    ?>
    <!--<div class="orderby-displaynumber">-->
    <div style="width:775px; height:27px; margin-top:34px; margin-bottom:15px;">
        <div class="round-left-27"></div>
        <div style="float:left; width:745px; height:27px; background: url('/templates/beez_20/css/images/new/round-center-27.png') repeat-x;" class="didact">
            <div class="floatleft" style="margin-left:10px;">
                <?php echo $this->orderByList['orderby']; ?>
            </div>
            <div class="vm-pagination" style="float:left; margin-left: 10px;">
                <?php echo $this->vmPagination->getPagesLinks(); ?>
                <span style="float:right"><?php echo $this->vmPagination->getPagesCounter(); ?></span>
            </div>
			<div style="float:right;" class="items-on-page"><?php echo $this->vmPagination->getLimitBox(); ?></div>
            <div class="showall" style="float: right; margin-top: 6px; margin-right:10px;">
                <?php $catid = JRequest::getInt('virtuemart_category_id'); ?>
                <?php echo "<a class='vmorder-link' href='/index.php?option=com_virtuemart&view=category&virtuemart_category_id=".$catid."'>Показать все</a>"; ?>
            </div>
        </div>
        <div class="round-right-27"></div>
        <div class="clear"></div>
    </div> <!-- end of orderby-displaynumber -->

    <h1 style="display:none;"><?php echo $this->category->category_name; ?></h1>

    <?php
// Category and Columns Counter
    $iBrowseCol = 1;
    $iBrowseProduct = 1;

// Calculating Products Per Row
    $BrowseProducts_per_row = $this->perRow;
    $Browsecellwidth = ' width'.floor ( 100 / $BrowseProducts_per_row );

// Separator
    $verticalseparator = " vertical-separator";

// Count products
    $BrowseTotalProducts = 0;
    foreach ( $this->products as $product ) {
        $BrowseTotalProducts ++;
    }

// Start the Output
    foreach ( $this->products as $product ) {

        // Show the horizontal seperator
        if ($iBrowseCol == 1 && $iBrowseProduct > $BrowseProducts_per_row) { ?>
            <div class="horizontal-separator"></div>
            <?php }

        // this is an indicator wether a row needs to be opened or not
        if ($iBrowseCol == 1) { ?>
	<div class="row" style="margin-left:-18px;">
	<?php }

        // Show the vertical seperator
        if ($iBrowseProduct == $BrowseProducts_per_row or $iBrowseProduct % $BrowseProducts_per_row == 0) {
            $show_vertical_separator = ' ';
        } else {
            $show_vertical_separator = $verticalseparator;
        }

        // Show Products ?>
        <div class="item-akc main61 product floatleft<?php echo $Browsecellwidth . $show_vertical_separator ?>">
		<!--SPAN AKC HIT NEW-->
		<?php $adate = strtotime(date("Y-m-d", strtotime(JDate::getInstance($product->created_on))) . " +1 week");$oldprice = $product->prices['priceWithoutTax'];
		$price = $product->prices['salesPrice'];$is_new = (time() <= $adate);
		$span_class  = "";
		if(in_array($product->virtuemart_product_id,$populardata)){ if(!$is_new){ $span_class = "b-hit"; } else { $span_class = "b-new";} } else { if($is_new){ $span_class = "b-new"; } }
		if($oldprice != $price){ $span_class = "b-akc"; } ?>
		<?php echo "<div style='display:none'>".json_encode($product)."</div>" ?>
            <span class="<?php echo $span_class; ?>"></span>
		<!--END : SPAN AKC HIT NEW-->
            <div style="margin: 10px 10px 15px; text-align: center; height:30px; font-size:12px;" class="vm-item-title">
                <?php echo JHTML::link($product->link, $product->product_name); ?>
            </div>

            <div style="width:100%; height:155px; text-align: center;">
				<?php echo '<a style="display:block; width:100%; height:100%;" href="' . $product->link . '">'; ?>
                <?php //echo $product->images[0]->displayMediaThumb('class="browseProductImage" border="0" title="'.$product->product_name.'" ',true,'class="modal"'); ?>
				<?php echo $product->images[0]->displayMediaThumb('class="browseProductImage" border="0" title="'.$product->product_name.'" ',false,''); ?>
				<?php echo '</a>'; ?>
            </div>
            <div style="clear:both;"></div>
            <div style="width:100%; height:70px; text-align: center;">
                <p class="product_s_desc" style="font-size:11px; padding: 0 7px;">
                    <?php echo shopFunctionsF::limitStringByWord($product->product_s_desc, 100, '...') ?>
                </p>
            </div>
            <?php $oldprice = $product->prices['priceWithoutTax'];
                $price = $product->prices['salesPrice'];
                if($oldprice == $price){ ?>
            <div style="height:27px; width:100%;">
                <p style="margin:0; text-align:center; color:#6a6a6a;">
                    Цена: <span style="color: #474747; font-weight:bold; font-size:18px;"><?php echo round($product->prices['salesPrice'],0,PHP_ROUND_HALF_UP); ?> руб.</span>
                </p>
            </div>
            <div style="height:27px; width:100%;">
            </div>
                <?php
                    } else { ?>
            <div style="height:27px; width:100%;">
                <p style="margin:0; text-align:center; color:#373737;">
                    Cтарая цена: <span style="color: #F90000;"><del><?php echo round($product->prices['priceWithoutTax'],0,PHP_ROUND_HALF_UP); ?> руб.</del></span>
                </p>
            </div>
            <div style="height:27px; width:100%;">
                <p style="margin:0; text-align:center; color:#474747;">
                    Цена: <span style="color: #3F8F03; font-weight:bold; font-size:18px;"><?php echo round($product->prices['salesPrice'],0,PHP_ROUND_HALF_UP); ?> руб.</span>
                </p>
            </div>
                  <?php } ?>
            <div class="addtocart-area" style="display:block; width:250px;">
                <form action="index.php" class="product" method="post">
                    <div class="addtocart-bar">
                        <input type="submit" style="display:inline-block; padding-bottom:2px;" title="В корзину" value="В корзину" class="addtocart-button" name="addtocart">
                    </div>
                    <input type="hidden" value="<?php echo $product->product_name; ?>" class="pname">
                    <input type="hidden" value="com_virtuemart" name="option">
                    <input type="hidden" value="cart" name="view">
                    <noscript>&lt;input type="hidden" name="task" value="add" /&gt;</noscript>
                    <input type="hidden" value="<?php echo $product->virtuemart_product_id; ?>" name="virtuemart_product_id[]">
                    <input type="hidden" value="<?php echo $product->virtuemart_category_id; ?>" name="virtuemart_category_id[]">
                </form>
            </div>
            <div class="add-to-fav" style="text-align: center;white-space:nowrap;width:250px; margin-top:5px;"><?php require(JPATH_BASE.DS."components/com_wishlist/template/addtofavorites_form.tpl.php"); ?></div>
            <div class="clear"></div>
		</div>
	<?php

        // Do we need to close the current row now?
        if ($iBrowseCol == $BrowseProducts_per_row || $iBrowseProduct == $BrowseTotalProducts) {?>
            <div class="clear"></div>
   </div> <!-- end of row -->
        <?php
            $iBrowseCol = 1;
        } else {
            $iBrowseCol ++;
        }

        $iBrowseProduct ++;
    } // end of foreach ( $this->products as $product )
// Do we need a final closing row tag?
    if ($iBrowseCol != 1) { ?>
    <div class="clear"></div>

    <?php
    }
    ?>
    <div style="width:775px; height:27px; margin-top:15px; margin-bottom:34px;">
        <div class="round-left-27"></div>
        <div style="float:left; width:745px; height:27px; background: url('/templates/beez_20/css/images/new/round-center-27.png') repeat-x;" class="didact">
            <div class="floatleft" style="margin-left:10px;">
                <?php echo $this->orderByList['orderby']; ?>
            </div>
            <div class="vm-pagination" style="float:left; margin-left: 10px;">
                <?php echo $this->vmPagination->getPagesLinks(); ?>
                <span style="float:right"><?php echo $this->vmPagination->getPagesCounter(); ?></span>
            </div>
            <div style="float:right;" class="items-on-page"><?php echo $this->vmPagination->getLimitBox(); ?></div>
            <div class="showall" style="float: right; margin-top: 6px; margin-right:10px;">
                <?php $catid = JRequest::getInt('virtuemart_category_id'); ?>
                <?php echo "<a class='vmorder-link' href='/index.php?option=com_virtuemart&view=category&virtuemart_category_id=".$catid."'>Показать все</a>"; ?>
            </div>
        </div>
        <div class="round-right-27"></div>
        <div class="clear"></div>
    </div> <!-- end of orderby-displaynumber -->
<?php } elseif ($this->search !==null ) { echo JText::_('COM_VIRTUEMART_NO_RESULT').($this->keyword? ' : ('. $this->keyword. ')' : ''); } else { echo "<span style='font-size:18px;'>В данной категории товары отсутствуют.</span>"; }
?>
</div><!-- end browse-view -->
<div class="category_description" style="color:#444444;">
    <?php if($this->category->category_description){
    echo "<h2 style='font-size:14px;'>Описание</h2>";
    echo $this->category->category_description;
} ?>
</div>
