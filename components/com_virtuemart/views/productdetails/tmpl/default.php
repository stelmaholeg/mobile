<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz
 * @author RolandD,
 * @todo handle child products
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 5698 2012-03-21 21:47:12Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$db	=& JFactory::getDBO();
$query = "SELECT virtuemart_product_id
              FROM `#__virtuemart_order_items`
             GROUP BY virtuemart_product_id
             ORDER BY COUNT(virtuemart_product_id) DESC LIMIT 5";
$db->setQuery($query);
$populardata = $db->loadResultArray();

// addon for joomla modal Box
JHTML::_('behavior.modal');
// JHTML::_('behavior.tooltip');
$url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component');
$document = JFactory::getDocument();
$document->addScriptDeclaration("
	jQuery(document).ready(function($) {
		$('a.ask-a-question').click( function(){
			$.facebox({
				iframe: '" . $url . "',
				rev: 'iframe|550|550'
			});
			return false ;
		});
		$('.additional-images a').mouseover(function() {
			var himg = this.href;
			var extension=himg.substring(himg.lastIndexOf('.')+1);
			if (extension =='png' || extension =='jpeg' || extension =='jpg' || extension =='gif') {
				$('.main-image img').attr('src',himg );
				$('.main-image img').parent().attr('href',himg );
			}
			console.log(extension)
		});
		$('img.additional-image').mouseover(function() {
			var himg = $(this).parent().attr('rel');
			var extension=himg.substring(himg.lastIndexOf('.')+1);
			if (extension =='png' || extension =='jpeg' || extension =='jpg' || extension =='gif') {
				$('.main-image img').attr('src','/'+himg );
				$('.main-image img').parent().attr('href','/'+himg );
			}
			console.log(extension)
		});
	});
");
/* Let's see if we found the product */
if (empty($this->product)) {
    echo JText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
    echo '<br /><br />  ' . $this->continue_link_html;
    return;
}
?>

<div class="productdetails-view">

    <?php // Product Navigation
    if (VmConfig::get('product_navigation', 1)) {
	?>
        <!--
        <div class="product-neighbours">
	    <?php
	    if (!empty($this->product->neighbours ['previous'][0])) {
		$prev_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['previous'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id);
		echo JHTML::_('link', $prev_link, $this->product->neighbours ['previous'][0]
			['product_name'], array('class' => 'previous-page'));
	    }
	    if (!empty($this->product->neighbours ['next'][0])) {
		$next_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['next'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id);
		echo JHTML::_('link', $next_link, $this->product->neighbours ['next'][0] ['product_name'], array('class' => 'next-page'));
	    }
	    ?>
    	<div class="clear"></div>
        </div>
        -->
    <?php } // Product Navigation END
    ?>

    <div class="product-detail-images" style="width:344px; height:390px; float:left;">
		<!--SPAN AKC HIT NEW-->
			<?php $adate = strtotime(date("Y-m-d", strtotime(JDate::getInstance($this->product->created_on))) . " +1 week");$oldprice = $this->product->prices['priceWithoutTax'];
			$price = $this->product->prices['salesPrice'];$is_new = (time() <= $adate);
			if($oldprice != $price){ $span_class = "b-akc"; }
			if(in_array($this->product->id,$populardata)){ if(!$is_new){ $span_class = "b-hit"; } else { $span_class = "b-new";} } else { if($is_new){ $span_class = "b-new"; } } ?>
				<span style="margin-left:223px;margin-top:10px" class="<?php echo $span_class; ?>"></span>
		<!--END : SPAN AKC HIT NEW-->
        <?php echo $this->loadTemplate('images'); ?>
    </div>
    <div style="margin-left:16px;width:634px;float:left">
        <?php // Product Title   ?>
        <h1 style="color:#2D6D8B;font-size:16px;font-family:Tahoma;text-align:center;margin-bottom:15px;font-weight:normal"><?php echo $this->product->product_name ?></h1>
        <?php // Product Title END   ?>
        <?php if (!empty($this->product->product_s_desc)) { ?>
        <div class="product-short-description" style="color:#3A3A3A; font-size:12px; font-weight: normal; text-align: justify; font-family: Tahoma;">
            <?php echo nl2br($this->product->product_s_desc); ?>
        </div>
        <?php } ?>
        <div style="width:360px;float:left">
            <span class="summary-title">Характеристики <?php echo $this->product->product_name ?>:</span>
            <?php if (!empty($this->product->customfieldsSorted['normal'])) {
            $this->position = 'normal';
            $this->tab = 'summ';
            echo $this->loadTemplate('customfields');
        }?>
        </div>
        <div style="width:254px;float:left;margin-left:20px">
            <div class="spacer-buy-area">
                <?php
                // Product Price
                if ($this->show_prices and (empty($this->product->images[0]) or $this->product->images[0]->file_is_downloadable == 0)) {
                    echo $this->loadTemplate('showprices');
                }
                ?>
                <?php
                // Add To Cart Button
// 			if (!empty($this->product->prices) and !empty($this->product->images[0]) and $this->product->images[0]->file_is_downloadable==0 ) {
                if (!VmConfig::get('use_as_catalog', 0) and !empty($this->product->prices)) {
                    echo $this->loadTemplate('addtocart2');
                }  // Add To Cart Button END
                ?>
                <div class="single-fav-link" style="margin-left:25px;margin-top:7px;height:28px">
                    <?php require(JPATH_BASE.DS."components/com_wishlist/template/addtofavorites_form.tpl.php"); ?>
                </div>
                <div style="margin-top:30px;">
                    <!-- AddThis Button BEGIN -->
                    <div class="addthis_toolbox addthis_default_style addthis_32x32_style" style="width:245px">
                        <a class="addthis_button_vk"></a>
                        <a class="addthis_button_odnoklassniki_ru"></a>
                        <a class="addthis_button_twitter"></a>
                        <a class="addthis_button_facebook"></a>
                        <a class="addthis_counter addthis_bubble_style"></a>
                    </div>
                    <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                    <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4ff837162f0c03c8"></script>
                    <!-- AddThis Button END -->

                </div>

                <?php
                // Availability Image
                /* TO DO add width and height to the image */
                if (!empty($this->product->product_availability)) {
                    $stockhandle = VmConfig::get('stockhandle', 'none');
                    if ($stockhandle == 'risetime' and ($this->product->product_in_stock - $this->product->product_ordered) < 1) { ?>
                        <div class="availability">
                            <?php echo JHTML::image(JURI::root() . VmConfig::get('assets_general_path') . 'images/availability/' . VmConfig::get('rised_availability', '7d.gif'), VmConfig::get('rised_availability', '7d.gif'), array('class' => 'availability')); ?>
                        </div>
                        <?php } else {
                        ?>
                        <div class="availability">
                            <?php echo JHTML::image(JURI::root() . VmConfig::get('assets_general_path') . 'images/availability/' . $this->product->product_availability, $this->product->product_availability, array('class' => 'availability')); ?>
                        </div>
                        <?php
                    }
                }
                ?>

                <?php
// Ask a question about this product
                if (VmConfig::get('ask_question', 1) == '1') {
                    ?>
                    <div class="ask-a-question">
                        <a title="" class="ask-a-question" href="<?php echo $url ?>" ><?php echo JText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a>
                        <!--<a class="ask-a-question modal" rel="{handler: 'iframe', size: {x: 700, y: 550}}" href="<?php echo $url ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a>-->
                    </div>
                    <?php }
                ?>

                <?php
                // Manufacturer of the Product
                if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id)) {
                    //echo $this->loadTemplate('manufacturer');
                }
                ?>

            </div>
        </div>
    </div>
    <div style="clear:both"></div>

    <?php echo $this->edit_link; ?>

    <?php
    // PDF - Print - Email Icon
    if (VmConfig::get('show_emailfriend') || VmConfig::get('show_printicon') || VmConfig::get('pdf_button_enable')) {
	?>

        <div class="icons">
	    <?php
	    //$link = (JVM_VERSION===1) ? 'index2.php' : 'index.php';
	    $link = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id;
	    $MailLink = 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component';

	    if (VmConfig::get('pdf_icon', 1) == '1') {
		echo $this->linkIcon($link . '&format=pdf', 'COM_VIRTUEMART_PDF', 'pdf_button', 'pdf_button_enable', false);
	    }
	    echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon');
	    echo $this->linkIcon($MailLink, 'COM_VIRTUEMART_EMAIL', 'emailButton', 'show_emailfriend');
	    ?>
    	<div class="clear"></div>
        </div>
    <?php } // PDF - Print - Email Icon END
    ?>




    <?php if (!empty($this->product->customfieldsSorted['ontop'])) {
	$this->position = 'ontop';
	echo $this->loadTemplate('customfields');
    } // Product Custom ontop end
    ?>

    <div>

	<div class="width20 floatright">

	</div>
	<div class="clear"></div>
    </div>

	<?php // event onContentBeforeDisplay
	echo $this->product->event->beforeDisplayContent; ?>


    <div style="width:1000px">
        <?php
        $document    = &JFactory::getDocument();
        $renderer    = $document->loadRenderer('modules');
        $options    = array('style' => 'xhtml');
        $position    = 'bottomslider';
        echo $renderer->render($position, $options, null);
        ?>
    </div>


    <?php
	// Product Description
	if (!empty($this->product->product_desc)) {
	    ?>
        <!--<div class="product-description">
	<?php /** @todo Test if content plugins modify the product description */ ?>
    	<span class="title"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE') ?></span>
	<?php echo $this->product->product_desc; ?>
        </div>-->
	<?php
    } // Product Description END?>
    <?php
    // Product Packaging
    $product_packaging = '';
    if ($this->product->packaging || $this->product->box) {
	?>
        <div class="product-packaging">

	    <?php
	    if ($this->product->packaging) {
		$product_packaging .= JText::_('COM_VIRTUEMART_PRODUCT_PACKAGING1') . $this->product->packaging;
		if ($this->product->box)
		    $product_packaging .= '<br />';
	    }
	    if ($this->product->box)
		$product_packaging .= JText::_('COM_VIRTUEMART_PRODUCT_PACKAGING2') . $this->product->box;
	    echo str_replace("{unit}", $this->product->product_unit ? $this->product->product_unit : JText::_('COM_VIRTUEMART_PRODUCT_FORM_UNIT_DEFAULT'), $product_packaging);
	    ?>
        </div>
    <?php } // Product Packaging END ?>
    <?php
    // Product Files
    // foreach ($this->product->images as $fkey => $file) {
    // Todo add downloadable files again
    // if( $file->filesize > 0.5) $filesize_display = ' ('. number_format($file->filesize, 2,',','.')." MB)";
    // else $filesize_display = ' ('. number_format($file->filesize*1024, 2,',','.')." KB)";
    /* Show pdf in a new Window, other file types will be offered as download */
    // $target = stristr($file->file_mimetype, "pdf") ? "_blank" : "_self";
    // $link = JRoute::_('index.php?view=productdetails&task=getfile&virtuemart_media_id='.$file->virtuemart_media_id.'&virtuemart_product_id='.$this->product->virtuemart_product_id);
    // echo JHTMl::_('link', $link, $file->file_title.$filesize_display, array('target' => $target));
    // }
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery( "#product-tab-detail" ).tabs();
    });
</script>
<div id="product-tab-detail" style="width:685px">
    <ul>
        <li><a title="" href="#tab-details">Спецификация</a></li>
        <li><a title="" href="#tab-obzors">Обзоры</a></li>
        <li><a title="" href="#tab-related">Аксессуары</a></li>
        <li><a title="" href="#tab-reviews">Отзывы</a></li>
    </ul>

   <div id="tab-details">
       <?php if (!empty($this->product->customfieldsSorted['normal'])) {
       $this->position = 'normal';
       $this->tab = 'full';
       echo $this->loadTemplate('customfields');
   }?>
   </div>
   <div id="tab-reviews">
   <?php echo $this->loadTemplate('reviews'); ?>
   </div>
   <div id="tab-related">
   <?php if (!empty($this->product->customfieldsRelatedProducts)) {
            echo $this->loadTemplate('relatedproducts');
        }
        if (!empty($this->product->customfieldsRelatedCategories)) {
	        echo $this->loadTemplate('relatedcategories');
    }?>
    </div>
    <div id="tab-obzors">
        <?php echo $this->product->product_desc; ?>
    </div>
</div>
    <?php
    $document    = &JFactory::getDocument();
    $renderer    = $document->loadRenderer('modules');
    $options    = array('style' => 'xhtml');
    $position    = 'single-vertical';
    echo $renderer->render($position, $options, null);
    ?>
    <?php
    // Product customfieldsRelatedCategories END
    // Show child categories
        if (VmConfig::get('showCategory', 1)) {
            echo $this->loadTemplate('showcategory');
        }
    ?>
    <?php // onContentAfterDisplay event
        echo $this->product->event->afterDisplayContent; ?>
</div>
