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
<div class="vmgroup<?php echo $params->get('moduleclass_sfx') ?>">

    <?php if ($headerText) { ?>
    <div class="vmheader"><?php echo $headerText ?></div>
    <?php
}
    if ($display_style == "div") {
        ?>
<!--<div class="items-slider">
    <div id="fi-sl-bw" style="float:left; height:120px; width:20px; font-size:18px;"></div>
    <div id="fi-sl-fw" style="float:right; height:120px; width:20px; font-size:18px;"></div>
    <div id="slider" class="wrapper">
        <li class="panel1">
        <?php // $i = 0;
//        foreach ($products as $product) {
//            if($i > 1){ $i=0; ?>
        </li>
        <li class="panel<?php // echo $i; ?> vmproduct<?php // echo $params->get('moduleclass_sfx'); ?>">-->
<!--        <?php // } $i++;  ?>
            <div class="<?php // echo $pwidth ?> <?php // echo $float ?>">
                <div class="spacer">
                    <?php
//                    if (!empty($product->images[0]))
//                        $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" style="border:0"', false);
//                    else $image = '';
//                    echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
//                    echo '<div class="clear"></div>';
//                    $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
//                        $product->virtuemart_category_id); ?>        <a
                    href="//<?php // echo $url ?>"><?php // echo $product->product_name ?></a>        <?php //    echo '<div class="clear"></div>';
//
//                    if ($show_price) {
//                        if (!empty($product->prices['salesPrice'])) echo $currency->createPriceDiv('salesPrice', '', $product->prices, true);
//                        if (!empty($product->prices['salesPriceWithDiscount'])) echo $currency->createPriceDiv('salesPriceWithDiscount', '', $product->prices, true);
//                    }
//                    if ($show_addtocart) echo mod_virtuemart_product::addtocart($product);
                    ?>
                </div>
            </div>
            <?php
//            if ($col == $products_per_row && $products_per_row && $col < $totalProd) {
//                echo "	</div><div style='clear:both;'>";
//                $col = 1;
//            } else {
//                $col++;
//            }
        } ?>
        </li>
    </div>
</div>-->
        <!--<br style='clear:both;'/>-->

        <?php
//    } else {
//        $last = count($products) - 1;
        ?>
<!--    <div class="items-slider">
        <div class="wrapper">
            <ul class="vmproduct<?php // echo $params->get('moduleclass_sfx'); ?>">
                <?php // foreach ($products as $product) : ?>
                <li class="<?php // echo $pwidth ?> <?php // echo $float ?>">
                    <?php
//                    if (!empty($product->images[0]))
//                        $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" style="border:0"', false);
//                    else $image = '';
//                    echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
//                    echo '<div class="clear"></div>';
//                    $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
//                        $product->virtuemart_category_id); ?>        <a
                    href="//<?php // echo $url ?>"><?php // echo $product->product_name ?></a>        <?php //    echo '<div class="clear"></div>';

//                    if ($show_price) {
//                        echo $currency->createPriceDiv('salesPrice', '', $product->prices, true);
//                        if ($product->prices['salesPriceWithDiscount'] > 0) echo $currency->createPriceDiv('salesPriceWithDiscount', '', $product->prices, true);
//                    }
//                    if ($show_addtocart) echo mod_virtuemart_product::addtocart($product);
                    ?>
                </li>
                <?php
//                if ($col == $products_per_row && $products_per_row && $last) {
//                    echo '
//            </ul><div class="clear"></div>
//            <ul  class="vmproduct' . $params->get('moduleclass_sfx') . '">';
//                    $col = 1;
//                } else {
//                    $col++;
//                }
//                $last--;
//            endforeach; ?>
            </ul>
        </div>
    </div>-->
        <div class="clear"></div>

        <?php
//    }
    if ($footerText) : ?>
        <div class="vmfooter<?php echo $params->get('moduleclass_sfx') ?>">
            <?php echo $footerText ?>
        </div>
        <?php endif; ?>
</div>