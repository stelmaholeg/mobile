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
<div class="vmgroup h-akcii">
    <?php if ($headerText) { ?>
    <div class="vmheader-green">
        <div class="h-left"></div>
        <div class="h-mid"><?php echo $headerText ?></div>
        <div class="h-right"></div>
    </div>
    <div>
    <?php } ?>
    <?php $last = count($products) - 1; ?>
    <div class="main-items-akc">
        <ul class="vmproduct<?php echo $params->get('moduleclass_sfx'); ?>">
            <?php foreach ($products as $product) : ?>
            <li class="vm-item <?php echo $pwidth ?> <?php echo $float ?>">
                <div class="vm-item-title" style="height:45px;">
                    <?php $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id); ?>
                    <a href="<?php echo $url ?>"><?php echo $product->product_name ?></a>
                </div>

                <?php if (!empty($product->images[0]))
                    $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" style="border:0"', false);
                else $image = '';?>
                <div style="height:175px; width:217px; text-align: center;">
          <?php echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name)); ?>
                </div>
                <div class="clear"></div>
                <div>
          <?php if ($show_price) {
                    // Цена со скидкой
                    if($product->prices['costPrice'] != $product->prices['salesPrice']){
                        echo "<p class='price price-title' style='color:#474747; text-align:right; padding:0 13px;font-size:14px;'>Цена: <span class='price price-value' style='font-size:14px; color:#474747;'><del>". round($product->prices['costPrice'],2) . ".</del></span></p>";
                        echo "<p class='price akc-price-title' style='color:#474747; text-align:right; padding:0 13px; font-size:14px;'>Цена по акции: <span class='price akc-price' style='color: #3F8F03; font-weight:bold; font-size:24px;'>" . round($product->prices['salesPrice'],2) . ".</span></p>";
                    } else {
                        echo "<p class='price price-title' style='color:#474747; text-align:right; height:59px; padding:0 13px;'>Цена: <span  class='price price-value'>". $product->prices['salesPrice'] . ".</span></p>";
                    }
                } ?>
					<div style="margin-left:30px;">
					<?php if ($show_addtocart) echo  mod_virtuemart_product::addtocart($product,"В корзину"); ?>
					</div>
                </div>
            </li>

            <?php
            if ($col == $products_per_row && $products_per_row && $last) {
                echo '</ul><div class="clear"></div><ul  class="vmproduct' . $params->get('moduleclass_sfx') . '">';
                $col = 1;
            } else {
                $col++;
            }
            $last--;
        endforeach; ?>
        </ul>
    </div>
    <div class="clear"></div>
    </div>
</div>
<div class="long-bestoff-bottom"></div>