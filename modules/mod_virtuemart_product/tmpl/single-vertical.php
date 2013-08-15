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
<div class="vmgroup v-akcii">
    <div class="vmheader-green-vert">
        Лучшие предложения
    </div>
    <div class="vertical-body">
    <?php $last = count($products) - 1; ?>
    <div class="main-items-akc">
        <ul class="vmproduct<?php echo $params->get('moduleclass_sfx'); ?>">
            <?php foreach ($products as $product) : ?>
            <li class="vm-item <?php echo $pwidth ?> <?php echo $float ?>" style="width:236px; height:118px;">
                <?php if (!empty($product->images[0]))
                $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" border="0"', false);
                else $image = '';?>
                <div class="vakc-image">
                    <?php echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name)); ?>
                </div>
                <div class="vakc-text">
                    <?php //echo $product->product_name; ?>
					<?php echo substr(str_replace(' ','  ',$product->product_name),0,50)."..."; ?>
                </div>
                <div class="clear"></div>
                <div class="vakc-price">
                    <?php echo "<span style='color:#F90000;'>" . round($product->prices['salesPrice'],2) . " Р</span>"; ?>
                </div>
                <div class="vakc-link">
                    <?php $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id); ?>
                    <a href="<?php echo $url ?>">Подробнее</a>
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
    <div class="bot"></div>
</div>