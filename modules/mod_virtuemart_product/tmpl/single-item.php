<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$col = 1;
$pwidth = ' width' . floor(100 / $products_per_row);
if ($products_per_row > 1) {
    $float = "floatleft";
}
else {
    $float = "center";
}
?>
<div style="width:993px;" class="vmgroup<?php echo $params->get('moduleclass_sfx') ?>">

    <?php if ($headerText) { ?>
    <div class="vmheader"><?php echo $headerText ?></div>
    <?php
}
    if ($display_style == "div") { ?>
        <style type="text/css" media="screen">
            .slides_container {
                height:204px;
                /*
                    border-top:solid 1px #cccccc;
                    border-bottom:solid 1px #cccccc;
                */
            }

            .slides_container div.l1 {
                width:970px;
                height:200px;
                display:block;
            }
        </style>
        <script>
            jQuery(function(){
                jQuery("#slides").slides({
                    generatePagination: false,
                    next: 'left-slide-btn',
                    prev: 'right-slide-btn'
                });
            });
        </script>
        <div class="right-slide-btn" style="float:right;">&nbsp;</div>
        <div class="left-slide-btn" style="float:left;">&nbsp;</div>
        <div id="slides" class="product-details-slides" style="width:1000px;">
            <div class="slides_container">
                <div class="l1">
                    <?php $i = 0; foreach ($products as $product) {
                        if (!empty($product->images[0]))
                            $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" border="0"', false);
                        else $image = '';
                        $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' .
                                         $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
                    if($i > 2){ $i=0; ?>
                </div>
                <div class="l1">
                <?php } ?>
                    <div class="single_item">
                        <div class="single_title">
                            <a href="<?php echo $url ?>"><?php echo $product->product_name ?></a>
                        </div>
                        <div class="clear"></div>
                        <div class="single_image">
                            <?php echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name)); ?>
                        </div>
                        <div class="single_description" style="text-align:left;">
                            <?php //echo shopFunctionsF::limitStringByWord($product->product_s_desc, 90, '...') ?>
							<?php echo mb_substr($product->product_s_desc, 0, 65). '...'; ?>
                        </div>
                        <div class="clear" style="height:15px;"></div>
                        <div class="single_price">
                            <?php if ($show_price) {
                                if (!empty($product->prices['salesPrice'])) echo $currency->createPriceDiv('salesPrice', '', $product->prices, true);
                                if (!empty($product->prices['salesPriceWithDiscount'])) echo $currency->createPriceDiv('salesPriceWithDiscount', '', $product->prices, true);
                            } ?>
                        </div>
                        <div style="float:left; height:25px;">
                        <?php if ($show_addtocart) {
                            echo mod_virtuemart_product::addtocart($product);
                        } ?>
                        </div>
                    <?php $i++; ?>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
        <br style='clear:both;'/>
<?php } ?>
</div>