<?php // no direct access 
defined('_JEXEC') or die('Restricted access');

$user =& JFactory::getUser();
if ($user->id > 0)
{
//Loading Main Component Stylesheet
JHTML::stylesheet("template.css", "components/com_wishlist/");

$my_page =& JFactory::getDocument();
$conf =& JFactory::getConfig();
$sitename = $conf->getValue('config.sitename');
$my_page->setTitle($sitename. ' - ' .JText::_( 'VM_FAVORITE_LIST' )); 
?>
<h2><?php echo JText::_( 'VM_FAVORITE_LIST' ); ?></h2>
<?php
	if (empty( $this->data )){ 
               	echo "<div class='fav_header'>". JText::_('VM_FAVORITE_EMPTY')."</div>"; 	
		}
	else{
			$itemid = JRequest::getInt('Itemid',  1);
			$prod_name = JRequest::getString('prod_name',  "");
			$mode = JRequest::getString('mode',  "");
			if ($prod_name != "" && $mode == "delete") {
			 echo "<div class='fav_header'>". JText::_('VM_DELETED_TITLE') ." <strong>".$prod_name." </strong> ". JText::_('VM_DELETED_TITLE2')."</span></div>"; 
			 } 
			 
//Initialize the Virtuemart Product Model Class
$productModel = new VirtueMartModelProduct();

foreach($this->data as $dataItem):
$product = $productModel->getProduct($dataItem->product_id);
$productModel->addImages($product);

$product_qty = $dataItem->product_qty;
$product_ord = $product_qty > 0 ? $product_qty : 1;

$url_favlist=JRoute::_("index.php?option=com_wishlist&view=favoriteslist&Itemid={$itemid}");

//generate button to remove from favorites list
$form_deletefavorite = "<form action='". $url_favlist ."' method='POST' name='deletefavo' id='". uniqid('deletefavo_') ."'>\n
<input type='submit' style='margin-left:18px; cursor:pointer;' class='deletefav_button' value='".JText::_('VM_REMOVE_FAVORITE')."' title='".JText::_('VM_REMOVE_FAVORITE')."' onclick=\"return confirm('".JText::_('VM_REMOVEFAV_CONFIRM')."')\" />
<input type='hidden' name='mode' value='delete' />\n
<input type='hidden' name='fav_id' value='". $dataItem->fav_id ."' />\n
<input type='hidden' name='prod_name' value='". $product->product_name ."' /> \n
</form>\n";
			
echo "<div class='fav_container'>";
echo "<div class='left-col'>";

//Display Linked Product Name
$url_vm = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.
$product->virtuemart_category_id);
echo "<div class='col_info'>";
echo "<span class='prod_name'>";
echo "<a href='".$url_vm."'>".$product->product_name."</a>";
echo "</span>";

//Display Product Price
$currency = CurrencyDisplay::getInstance( );
echo "<p><span class='prod_price'>";
if (!empty($product->prices['salesPrice'] ) ) echo $currency->createPriceDiv('salesPrice','',$product->prices,true);
//if (!empty($product->prices['salesPriceWithDiscount']) ) echo $currency->createPriceDiv('salesPriceWithDiscount','',$product->prices,true);
echo "</span></p>";			
echo "</div>";

//Display Delete Favorite Form
echo "<div class='col_favorite'>";
echo $form_deletefavorite;
echo "</div>";
echo "</div>";

//Display Linked Product Image
if (!empty($product->images[0]) ) $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" style="border:none"',false) ;
else $image = '';	
echo "<div class='col_image'>";
echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id),$image,array('title' => $product->product_name) );		
echo "</div>";

//Display Add To Cart Form
echo "<div class='col_controls'>";
echo FavoritesModelFavoriteslist::addtocart($product,$product_ord);
echo "</div>";
echo "</div>";
echo "<hr style='margin-bottom: 10px; margin-top: 20px;'/>";
endforeach; ?>

<?php if(trim($this->pagination->getPagesLinks()) != "") {?>
<div class="jcb_pagination"><?php echo $this->pagination->getPagesLinks(); ?> - <?php echo $this->pagination->getPagesCounter(); ?></div>
<?php } ?>
<?php } 
}
else { ?>
<div class="fav_title"><?php echo JText::_( 'VM_SHARELIST_ERROR' ); ?></div>
<?php echo "<div class='fav_header'>". JText::_('VM_SHARELIST_DENY')."</div>"; ?>
<p style="padding:20px 0 20px 0"><input type="button" class="modns button art-button art-button addtocart_button" value="<?php echo JText::_( 'VM_SHARELIST_BACK' ); ?>" title="<?php echo JText::_( 'VM_SHARELIST_BACK' ); ?>" onclick="javascript:history.back()" /></p>
<?php
}
vmJsApi::jQuery();
vmJsApi::jPrice();
vmJsApi::cssSite();
?>
