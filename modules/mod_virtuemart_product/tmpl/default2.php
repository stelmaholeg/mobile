<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$col = 1;
$pwidth = ' width' . floor(100 / $products_per_row);
if ($products_per_row > 1) {
    $float = "floatleft";
} else {
    $float = "center";
} ?>
<div class="vmgroup<?php echo $params->get('moduleclass_sfx') ?>">
    <?php if ($headerText) { ?>
    <div class="vmheader"><?php echo $headerText ?></div>
    <?php
}
$last = count($products) - 1; ?>
<div>
    <ul class="vmproduct<?php echo $params->get('moduleclass_sfx'); ?>">
        <?php foreach ($products as $product) : ?>
        <li class="<?php echo $pwidth ?> <?php echo $float ?>">
            <?php
            if (!empty($product->images[0]))
                $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" border="0" height="50px"', false);
            else $image = '';
            echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
            echo '<div class="clear"></div>';
            $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
                $product->virtuemart_category_id); ?>        <a
            href="<?php echo $url ?>"><?php echo $product->product_name ?></a>        <?php    echo '<div class="clear"></div>';

            if ($show_price) {
                echo $currency->createPriceDiv('salesPrice', '', $product->prices, true);
                if ($product->prices['salesPriceWithDiscount'] > 0) echo $currency->createPriceDiv('salesPriceWithDiscount', '', $product->prices, true);
            }
            if ($show_addtocart) echo mod_virtuemart_product::addtocart($product); ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="clear"></div>
</div>